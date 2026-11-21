<?php

use App\Enums\SystemRoles;
use App\Filament\Project\Resources\Specimens\Pages\ListSpecimens;
use App\Models\Arm;
use App\Models\Event;
use App\Models\Project;
use App\Models\Site;
use App\Models\Specimen;
use App\Models\Specimentype;
use App\Models\Subject;
use App\Models\SubjectEvent;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Session;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

it('shows the event name for specimens whose subject is at another site', function (): void {
    $project = Project::factory()
        ->for($this->team)
        ->for($this->adminuser, 'leader')
        ->has(Site::factory()->count(2))
        ->create();
    [$memberSite, $otherSite] = $project->sites;

    $projectAdminRole = $project->roles()->create(['name' => 'Admin', 'guard_name' => 'web']);
    $projectAdmin = User::factory()->create(['system_role' => SystemRoles::User]);
    $project->members()->attach($projectAdmin->id, [
        'site_id' => $memberSite->id,
        'role_id' => $projectAdminRole->id,
    ]);

    $subject = Subject::factory()
        ->for($project)
        ->for($otherSite, 'site')
        ->for($this->adminuser)
        ->create();
    $event = Event::factory()->for(Arm::factory()->for($project))->create();
    $subjectEvent = SubjectEvent::create([
        'subject_id' => $subject->id,
        'event_id' => $event->id,
        'status' => 3,
    ]);
    $specimen = Specimen::factory()->create([
        'specimenType_id' => Specimentype::factory()->for($project)->create()->id,
        'subject_event_id' => $subjectEvent->id,
        'site_id' => $otherSite->id,
        'project_id' => $project->id,
    ]);

    actingAs($projectAdmin);
    Filament::setCurrentPanel('project');
    Filament::bootCurrentPanel();
    Session::put('currentProject', $project);

    livewire(ListSpecimens::class)
        ->assertCanSeeTableRecords([$specimen])
        ->assertTableColumnStateSet('subjectEvent.event.name', $event->name, $specimen);
});
