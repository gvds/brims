<?php

use App\Enums\ManifestStatus;
use App\Enums\SpecimenStatus;
use App\Models\Arm;
use App\Models\Event;
use App\Models\Manifest;
use App\Models\Project;
use App\Models\Site;
use App\Models\Specimen;
use App\Models\Specimentype;
use App\Models\Subject;
use App\Models\SubjectEvent;

use function Pest\Laravel\actingAs;

beforeEach(function (): void {
    actingAs($this->adminuser);

    $this->project = Project::factory()->create([
        'team_id' => $this->team->id,
        'leader_id' => $this->adminuser->id,
    ]);

    $this->sourceSite = Site::factory()->create([
        'project_id' => $this->project->id,
        'name' => 'SourceSite',
    ]);

    $this->destinationSite = Site::factory()->create([
        'project_id' => $this->project->id,
        'name' => 'DestSite',
    ]);

    $this->specimenType = Specimentype::factory()->create([
        'project_id' => $this->project->id,
    ]);

    $arm = Arm::factory()->create([
        'project_id' => $this->project->id,
        'arm_num' => 1,
    ]);

    $event = Event::factory()->create([
        'arm_id' => $arm->id,
    ]);

    $subject = Subject::factory()->create([
        'project_id' => $this->project->id,
        'site_id' => $this->sourceSite->id,
        'user_id' => $this->adminuser->id,
        'subjectID' => 'TST00001',
    ]);

    $this->subjectEvent = SubjectEvent::create([
        'subject_id' => $subject->id,
        'event_id' => $event->id,
        'iteration' => 1,
    ]);
});

it('updates manifest_items, specimen site, and specimen status on receive', function (): void {
    $specimen = Specimen::factory()->create([
        'subject_event_id' => $this->subjectEvent->id,
        'specimenType_id' => $this->specimenType->id,
        'site_id' => $this->sourceSite->id,
        'project_id' => $this->project->id,
        'status' => SpecimenStatus::Transferred,
        'aliquot' => 1,
        'loggedBy_id' => $this->adminuser->id,
        'loggedAt' => now(),
    ]);

    $manifest = Manifest::create([
        'project_id' => $this->project->id,
        'user_id' => $this->adminuser->id,
        'sourceSite_id' => $this->sourceSite->id,
        'destinationSite_id' => $this->destinationSite->id,
        'specimenTypes' => [$this->specimenType->id],
        'status' => ManifestStatus::Shipped,
        'shippedDate' => now()->subDay(),
    ]);

    $manifest->specimens()->attach($specimen->id, [
        'priorSpecimenStatus' => SpecimenStatus::InStorage,
    ]);

    $manifest->receive();

    $manifest->refresh();
    expect($manifest->status)->toBe(ManifestStatus::Received);
    expect($manifest->receivedDate)->not->toBeNull();
    expect($manifest->receivedBy_id)->toBe($this->adminuser->id);

    $specimen->refresh();
    expect($specimen->status)->toBe(SpecimenStatus::Received);
    expect($specimen->site_id)->toBe($this->destinationSite->id);

    $this->assertDatabaseHas('manifest_items', [
        'manifest_id' => $manifest->id,
        'specimen_id' => $specimen->id,
        'received' => true,
    ]);

    $pivotItem = $manifest->specimens()->withoutGlobalScopes()->first();
    expect($pivotItem->pivot->received)->toBeTrue();
    expect($pivotItem->pivot->receivedTime)->not->toBeNull();
});

it('creates an audit log entry when receiving a manifest', function (): void {
    $specimen = Specimen::factory()->create([
        'subject_event_id' => $this->subjectEvent->id,
        'specimenType_id' => $this->specimenType->id,
        'site_id' => $this->sourceSite->id,
        'project_id' => $this->project->id,
        'status' => SpecimenStatus::Transferred,
        'aliquot' => 1,
        'loggedBy_id' => $this->adminuser->id,
        'loggedAt' => now(),
    ]);

    $manifest = Manifest::create([
        'project_id' => $this->project->id,
        'user_id' => $this->adminuser->id,
        'sourceSite_id' => $this->sourceSite->id,
        'destinationSite_id' => $this->destinationSite->id,
        'specimenTypes' => [$this->specimenType->id],
        'status' => ManifestStatus::Shipped,
        'shippedDate' => now()->subDay(),
    ]);

    $manifest->specimens()->attach($specimen->id, [
        'priorSpecimenStatus' => SpecimenStatus::InStorage,
    ]);

    $manifest->receive();

    $this->assertDatabaseHas('specimen_logs', [
        'specimen_id' => $specimen->id,
        'previous_status' => SpecimenStatus::Transferred->value,
        'new_status' => SpecimenStatus::Received->value,
        'changed_by' => $this->adminuser->id,
    ]);
});

it('throws an exception when receiving a non-shipped manifest', function (): void {
    $manifest = Manifest::create([
        'project_id' => $this->project->id,
        'user_id' => $this->adminuser->id,
        'sourceSite_id' => $this->sourceSite->id,
        'destinationSite_id' => $this->destinationSite->id,
        'specimenTypes' => [$this->specimenType->id],
        'status' => ManifestStatus::Open,
    ]);

    $manifest->receive();
})->throws(Exception::class, 'Only manifests with status "Shipped" can be received.');

it('receives a manifest with multiple specimens', function (): void {
    $specimens = collect([1, 2, 3])->map(fn(int $aliquot) => Specimen::factory()->create([
        'subject_event_id' => $this->subjectEvent->id,
        'specimenType_id' => $this->specimenType->id,
        'site_id' => $this->sourceSite->id,
        'project_id' => $this->project->id,
        'status' => SpecimenStatus::Transferred,
        'aliquot' => $aliquot,
        'loggedBy_id' => $this->adminuser->id,
        'loggedAt' => now(),
    ]));

    $manifest = Manifest::create([
        'project_id' => $this->project->id,
        'user_id' => $this->adminuser->id,
        'sourceSite_id' => $this->sourceSite->id,
        'destinationSite_id' => $this->destinationSite->id,
        'specimenTypes' => [$this->specimenType->id],
        'status' => ManifestStatus::Shipped,
        'shippedDate' => now()->subDay(),
    ]);

    foreach ($specimens as $specimen) {
        $manifest->specimens()->attach($specimen->id, [
            'priorSpecimenStatus' => SpecimenStatus::InStorage,
        ]);
    }

    $manifest->receive();

    foreach ($specimens as $specimen) {
        $specimen->refresh();
        expect($specimen->status)->toBe(SpecimenStatus::Received);
        expect($specimen->site_id)->toBe($this->destinationSite->id);

        $this->assertDatabaseHas('manifest_items', [
            'manifest_id' => $manifest->id,
            'specimen_id' => $specimen->id,
            'received' => true,
        ]);
    }
});
