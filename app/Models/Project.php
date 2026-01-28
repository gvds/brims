<?php

namespace App\Models;

use App\Models\Scopes\ProjectScope;
use Exception;
use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

#[ScopedBy([ProjectScope::class])]
class Project extends Model implements HasName
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function arms(): HasMany
    {
        return $this->hasMany(Arm::class);
    }

    public function events(): HasManyThrough
    {
        return $this->hasManyThrough(Event::class, Arm::class);
    }

    public function sites(): HasMany
    {
        return $this->hasMany(Site::class);
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }

    public function studies(): HasMany
    {
        return $this->hasMany(Study::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_member')
            ->using(ProjectMember::class)
            ->withPivot(['id', 'role_id', 'site_id', 'substitute_id'])
            ->withTimestamps();
    }

    public function specimentypes(): HasMany
    {
        return $this->hasMany(Specimentype::class);
    }

    public function specimens(): HasMany
    {
        return $this->hasMany(Specimen::class);
    }

    public function labware(): HasMany
    {
        return $this->hasMany(Labware::class);
    }

    public function publications(): HasMany
    {
        return $this->hasMany(Publication::class);
    }

    public function importValueMappings(): HasMany
    {
        return $this->hasMany(ImportValueMapping::class);
    }

    public function getFilamentName(): string
    {
        return $this->title;
    }

    /** @return HasMany<\App\Models\Role, self> */
    public function roles(): HasMany
    {
        return $this->hasMany(\App\Models\Role::class);
    }

    public function setupREDCapProject(): void
    {
        // Setup REDCap project for this project
        $role = Role::create([
            'project_id' => $this->id,
            'name' => 'Admin',
            'guard_name' => 'web',
        ]);

        $redcap_leader = $this->getREDCapUser($this->redcapProject_id, $this->leader);
        if (empty($redcap_leader)) {
            throw new Exception('The assigned leader\'s username was not found in the REDCap project');
        }
        $token = $this->getOrGenerateToken($redcap_leader);
        // Create sites entries from REDCap DAGS
        $redcap_dags = DB::connection('redcap')->select("select * from redcap_data_access_groups where project_id = " . $this->redcapProject_id);
        foreach ($redcap_dags as $dag) {
            $site = Site::create([
                'project_id' => $this->id,
                'name' => $dag->group_name,
                'description' => 'REDCap Data Access Group ' . $dag->group_name,
            ]);
            if ($dag->group_id == $redcap_leader[0]->group_id) {
                $leader_site = $site->id;
            }
        }
        $this->members()->attach($this->leader, ['role_id' => $role->id, 'site_id' => $leader_site ?? null]);


        if (Auth::user()->id !== $this->leader_id) {
            $redcap_user = $this->getREDCapUser($this->redcapProject_id, Auth::user());
            if (empty($redcap_user)) {
                throw new Exception('The assigned current user\'s username was not found in the REDCap project');
            }
            foreach ($redcap_dags as $dag) {
                if ($dag->group_id == $redcap_user[0]->group_id) {
                    $user_site = Site::where('name', $dag->group_name)->where('project_id', $this->id)->first()->id;
                }
            }
            $this->members()->attach(Auth::user(), ['role_id' => $role->id, 'site_id' => $user_site ?? null]);
        }

        $redcap_arms = $this->redcap_arms($token);
        dd($redcap_arms);
        if (isset($redcap_arms['error'])) {
            if ($redcap_arms["error"] === "You cannot export arms for classic projects") {
                $redcap_arms = collect(json_decode('[{"arm_num":1,"name":"Arm 1"}]'));
            } else {
                throw new Exception($redcap_arms['error']);
            }
        }
        foreach ($redcap_arms as $redcap_arm) {
            $arm = Arm::create([
                'project_id' => $this->id,
                'name' => $redcap_arm->name,
                'redcap_arm_id' => $redcap_arm->arm_id,
                'arm_num' => $redcap_arm->arm_num
            ]);

            // Create events
            $redcap_events = $this->redcap_events($token, [$arm->arm_num]);
            $event_order = 1;
            foreach ($redcap_events as $redcap_event) {
                Event::create([
                    'arm_id' => $arm->id,
                    'name' => $redcap_event->event_name,
                    'offset' => $redcap_event->day_offset,
                    'offset_ante_window' => $redcap_event->offset_min,
                    'offset_post_window' => $redcap_event->offset_max,
                    'event_order' => $event_order++,
                    'redcap_event_id' => $redcap_event->event_id,
                    'autolog' => $redcap_event->day_offset == 0 ? true : false,
                ]);
            }
        }
    }

    private function getREDCapUser($redcapProject_id, $user)
    {
        return DB::connection('redcap')->select(
            "SELECT * from redcap_user_rights left join redcap_data_access_groups on
                    redcap_user_rights.group_id = redcap_data_access_groups.group_id and
                    redcap_user_rights.project_id = redcap_data_access_groups.project_id
                    where redcap_user_rights.project_id = $redcapProject_id and
                    redcap_user_rights.username = '$user->username'"
        );
    }

    private function getOrGenerateToken($redcap_user)
    {
        if (is_null($redcap_user[0]->api_token)) {
            for ($i = 0; $i < 5; $i++) {
                $token = strtoupper(bin2hex(random_bytes(16)));
                $duplicate = DB::connection('redcap')->select(
                    "SELECT count(api_token) as found FROM redcap_user_rights WHERE api_token = '$token'"
                );
                if (!$duplicate[0]->found) {
                    break;
                }
                throw new Exception('Could not create unique API token for the project leader in the REDCap database');
            }
            DB::connection('redcap')->update(
                "UPDATE redcap_user_rights SET api_token = '$token' WHERE
                redcap_user_rights.project_id = $this->redcapProject_id and
                redcap_user_rights.username = '$this->leader->username'"
            );
            return $token;
        } else {
            return $redcap_user[0]->api_token;
        }
    }

    private function redcap_arms($redcap_api_token)
    {
        $params = [
            'content' => 'arm'
        ];
        $arms = $this->curl($params, $redcap_api_token);
        return collect(json_decode($arms))->sortBy('arm_num');
    }

    private function redcap_events($redcap_api_token, $arms = [])
    {
        $params = [
            'content' => 'event',
            'arms' => $arms
        ];
        $events = $this->curl($params, $redcap_api_token);
        return collect(json_decode($events))->sortBy('day_offset');
    }

    private function curl(array $params, $redcap_api_token)
    {
        // $team = auth()->user()->teams->where('teams.id', session('currentProject'))->first();
        // $redcap_api_token = $team->pivot->redcap_api_token;

        $fields = array(
            'token'   => $redcap_api_token,
            'format'  => 'json',
            'returnFormat' => 'json'
        );

        $fields = array_merge($fields, $params);

        $data = array(
            'token' => $redcap_api_token,
            'content' => 'arm',
            'format' => 'json',
            'returnFormat' => 'json'
        );
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
}
