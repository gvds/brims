<?php

use App\Filament\Exports\SpecimenExporter;
use App\Models\Arm;
use App\Models\Event;
use App\Models\Project;
use App\Models\Site;
use App\Models\Specimen;
use App\Models\Specimentype;
use App\Models\Subject;
use App\Models\SubjectEvent;
use Filament\Actions\Exports\Models\Export;

use function Pest\Laravel\actingAs;

it('exports the arm name of the specimen subject event', function (): void {
    actingAs($this->adminuser);

    $project = Project::factory()->create([
        'team_id' => $this->team->id,
        'leader_id' => $this->adminuser->id,
    ]);
    $site = Site::factory()->create(['project_id' => $project->id]);
    $arm = Arm::factory()->create(['project_id' => $project->id, 'arm_num' => 1, 'name' => 'Treatment Arm']);
    $event = Event::factory()->create(['arm_id' => $arm->id]);
    $subject = Subject::factory()->create([
        'project_id' => $project->id,
        'site_id' => $site->id,
        'user_id' => $this->adminuser->id,
    ]);
    $subjectEvent = SubjectEvent::create([
        'subject_id' => $subject->id,
        'event_id' => $event->id,
        'iteration' => 1,
    ]);
    $specimen = Specimen::factory()->create([
        'subject_event_id' => $subjectEvent->id,
        'specimenType_id' => Specimentype::factory()->create(['project_id' => $project->id])->id,
        'site_id' => $site->id,
        'origin_site_id' => $site->id,
        'project_id' => $project->id,
        'aliquot' => 1,
    ]);

    $columnMap = [
        'subjectEvent.event.arm.name' => 'Arm Name',
        'subjectEvent.event.name' => 'Event Name',
    ];
    $exporter = new SpecimenExporter(new Export, $columnMap, []);

    $query = Specimen::query();
    foreach ($exporter->getCachedColumns() as $column) {
        $column->applyEagerLoading($query);
    }

    expect($exporter($query->find($specimen->id)))->toBe(['Treatment Arm', $event->name]);
});
