<?php

use App\Filament\Project\Widgets\ProjectProgress;
use App\Models\Arm;
use App\Models\Project;
use App\Models\Site;
use App\Models\Subject;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Date;

it('charts cumulative enrolment per site by month from the submission date', function (): void {
    Date::setTestNow('2026-04-15');

    $project = Project::factory()
        ->for(Team::factory())
        ->for(User::factory(), 'leader')
        ->create(['redcapProject_id' => null, 'submission_date' => '2026-01-20']);
    session(['currentProject' => $project]);

    $siteA = Site::factory()->for($project)->create(['name' => 'Alpha']);
    $siteB = Site::factory()->for($project)->create(['name' => 'Beta']);
    $user = User::factory()->create();
    $arm = Arm::factory()->create(['project_id' => $project->id, 'arm_num' => 1]);

    $enrol = function (Site $site, string $date, string $id) use ($project, $user, $arm): void {
        Subject::factory()->create([
            'subjectID' => $id,
            'project_id' => $project->id,
            'site_id' => $site->id,
            'user_id' => $user->id,
            'arm_id' => $arm->id,
            'enrolDate' => $date,
        ]);
    };

    $enrol($siteA, '2026-01-25', 'A1');
    $enrol($siteA, '2026-01-26', 'A2');
    $enrol($siteA, '2026-03-02', 'A3');
    $enrol($siteB, '2026-02-10', 'B1');

    $widget = new ProjectProgress;
    $data = (new ReflectionMethod($widget, 'getData'))->invoke($widget);

    expect($data['labels'])->toBe(['Jan 2026', 'Feb 2026', 'Mar 2026', 'Apr 2026'])
        ->and($data['datasets'])->toHaveCount(2)
        ->and($data['datasets'][0]['label'])->toBe('Alpha')
        ->and($data['datasets'][0]['data'])->toBe([2, 2, 3, 3])
        ->and($data['datasets'][1]['label'])->toBe('Beta')
        ->and($data['datasets'][1]['data'])->toBe([0, 1, 1, 1])
        ->and($data['datasets'][0]['borderColor'])->not->toBe($data['datasets'][1]['borderColor']);

    Date::setTestNow();
});
