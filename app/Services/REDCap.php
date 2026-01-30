<?php

namespace App\Services;

use App\Models\Arm;
use App\Models\Event;
use App\Models\Role;
use App\Models\Site;
use App\Models\Subject;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class REDCap
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    private static function curl(array $params, $redcap_api_token, array $data = [])
    {

        $fields = array(
            'token'   => $redcap_api_token,
            'format'  => 'json',
            'type'    => 'flat',
            'returnFormat' => 'json',
            'data'    => json_encode([$data])
        );

        $fields = array_merge($fields, $params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, config('services.redcap.url'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields, '', '&'));

        return curl_exec($ch);
    }

    public static function setupREDCapProject($project): void
    {
        $role = Role::create([
            'project_id' => $project->id,
            'name' => 'Admin',
            'guard_name' => 'web',
        ]);

        $redcap_user = self::getREDCapUser($project->redcapProject_id, Auth::user());
        if (empty($redcap_user)) {
            throw new Exception('The assigned current user\'s username was not found in the REDCap project');
        }
        $redcap_user = $redcap_user[0];

        $user_token = self::getOrGenerateToken($redcap_user, $project);

        // Create sites entries from REDCap DAGS
        $redcap_dags = DB::connection('redcap')->select("select * from redcap_data_access_groups where project_id = " . $project->redcapProject_id);
        foreach ($redcap_dags as $dag) {
            $site = Site::create([
                'project_id' => $project->id,
                'name' => $dag->group_name,
                'description' => 'REDCap Data Access Group ' . $dag->group_name,
            ]);
            if ($dag->group_id == $redcap_user->group_id) {
                $user_site = $site->id;
            }
        }

        $project->members()->attach(Auth::user(), ['role_id' => $role->id, 'site_id' => $user_site ?? null, 'redcap_token' => $user_token]);

        // Add project leader if different from current user
        if ($project->leader_id !== Auth::id()) {
            $redcap_leader = self::getREDCapUser($project->redcapProject_id, $project->leader);
            if (empty($redcap_leader)) {
                throw new Exception('The assigned leader\'s username was not found in the REDCap project');
            }
            $redcap_leader = $redcap_leader[0];
            $leader_token = self::getOrGenerateToken($redcap_leader, $project);
            foreach ($redcap_dags as $dag) {
                if ($dag->group_id == $redcap_leader->group_id) {
                    $leader_site = Site::where('name', $dag->group_name)->where('project_id', $project->id)->first()->id;
                }
            }
            $project->members()->attach($project->leader, ['role_id' => $role->id, 'site_id' => $leader_site ?? null, 'redcap_token' => $leader_token]);
        }

        $redcap_arms = self::redcap_arms($user_token);
        if (isset($redcap_arms['error'])) {
            if ($redcap_arms["error"] === "You cannot export arms for classic projects") {
                $redcap_arms = collect(json_decode('[{"arm_num":1,"name":"Arm 1"}]'));
            } else {
                throw new Exception($redcap_arms['error']);
            }
        }
        foreach ($redcap_arms as $redcap_arm) {
            $arm = Arm::create([
                'project_id' => $project->id,
                'name' => $redcap_arm->name,
                // 'redcap_arm_id' => $redcap_arm->arm_id,
                'arm_num' => $redcap_arm->arm_num
            ]);

            // Create events
            $redcap_events = self::redcap_events($user_token, [$arm->arm_num]);
            $event_order = 1;
            foreach ($redcap_events as $redcap_event) {
                Event::create([
                    'arm_id' => $arm->id,
                    'name' => $redcap_event->event_name,
                    'offset' => $redcap_event->day_offset,
                    'offset_ante_window' => $redcap_event->offset_min,
                    'offset_post_window' => $redcap_event->offset_max,
                    'event_order' => $event_order++,
                    // 'redcap_event_id' => $redcap_event->event_id,
                    'autolog' => $redcap_event->day_offset == 0 ? true : false,
                ]);
            }
        }
    }

    private static function getREDCapUser($redcapProject_id, $user)
    {
        return DB::connection('redcap')->select(
            "SELECT * from redcap_user_rights left join redcap_data_access_groups on
                    redcap_user_rights.group_id = redcap_data_access_groups.group_id and
                    redcap_user_rights.project_id = redcap_data_access_groups.project_id
                    where redcap_user_rights.project_id = $redcapProject_id and
                    redcap_user_rights.username = '$user->username'"
        );
    }

    private static function getOrGenerateToken($redcap_user, $project)
    {
        if (is_null($redcap_user->api_token)) {
            for ($i = 0; $i < 5; $i++) {
                $token = strtoupper(bin2hex(random_bytes(16)));
                $duplicate = DB::connection('redcap')->select(
                    "SELECT count(api_token) as found FROM redcap_user_rights WHERE api_token = '$token'"
                );
                if (!$duplicate[0]->found) {
                    break;
                }
                throw new Exception('Could not create unique API token for the project user in the REDCap database');
            }
            DB::connection('redcap')->update(
                "UPDATE redcap_user_rights SET api_token = '$token' WHERE
                redcap_user_rights.project_id = $project->redcapProject_id and
                redcap_user_rights.username = '$project->leader->username'"
            );
            return $token;
        } else {
            return $redcap_user->api_token;
        }
    }

    private static function redcap_arms($redcap_api_token)
    {
        $params = [
            'content' => 'arm'
        ];
        $arms = self::curl($params, $redcap_api_token);
        return collect(json_decode($arms))->sortBy('arm_num');
    }

    private static function redcap_events($redcap_api_token, $arms = [])
    {
        $params = [
            'content' => 'event',
            'arms' => $arms
        ];
        $events = self::curl($params, $redcap_api_token);
        return collect(json_decode($events))->sortBy('day_offset');
    }

    public static function createREDCapRecord(Subject $subject, int $arm_id): void
    {
        $arm = Arm::find($arm_id);
        $params = [
            'content' => 'event',
            'arms' => [$arm->arm_num]
        ];

        $project = session('currentProject');

        $token = $project->members()->where('user_id', Auth::id())->first()->pivot->redcap_token;
        if (is_null($token)) {
            throw new Exception('No REDCap API token found for the current user for this project');
        }

        $events = self::curl($params, $token);
        $events = json_decode($events, true);
        dd($events);

        if (array_key_exists('error', $events)) {
            throw new Exception('REDCap Error: ' . $events['error']);
        }
        $event_name = $events[0]['unique_event_name'];

        // DAG can only be specified if user does not belong to a DAG; We would have to include this field in the enrollment form is needed
        // $dag = strtolower($project->members()->where('user_id', Auth::id())->first()->pivot->site->name);

        $params = [
            'content' => 'record',
        ];
        $data = [
            'record_id' => $subject->subjectID,
            'redcap_event_name' => $event_name,
            // 'redcap_data_access_group' => $dag,
        ];
        $response = self::curl($params, $token, $data);
        $returnmsg = json_decode($response, true);
        if (array_key_exists("error", $returnmsg)) {
            throw new Exception($returnmsg['error']);
        } elseif ($returnmsg['count'] === 0) {
            throw new Exception('REDCap record was not created');
        }
    }
}
