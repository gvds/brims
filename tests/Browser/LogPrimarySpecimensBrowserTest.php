<?php

use App\Enums\SpecimenStatus;
use App\Models\Arm;
use App\Models\Event;
use App\Models\Labware;
use App\Models\Project;
use App\Models\Site;
use App\Models\Specimen;
use App\Models\Specimentype;
use App\Models\Subject;
use App\Models\SubjectEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

use function Pest\Laravel\actingAs;

beforeEach(function (): void {
    $this->user = $this->adminuser;
    actingAs($this->user);

    $this->project = Project::factory()
        ->for($this->team)
        ->for($this->user, 'leader')
        ->has(Site::factory()->count(2))
        ->create();

    // Attach user to project with site
    $this->project->members()->attach($this->user->id, [
        'site_id' => $this->project->sites->first()->id,
        'role' => 'Admin',
    ]);

    // Create labware for the project
    $this->labware = Labware::factory()
        ->for($this->project)
        ->create([
            'barcodeFormat' => '[A-Z]{2}[0-9]{4}',
        ]);

    // Create primary specimen types
    $this->primarySpecimenTypes = Specimentype::factory()
        ->count(3)
        ->for($this->project)
        ->for($this->labware, 'labware')
        ->create([
            'primary' => true,
            'specimenGroup' => 'Blood',
            'aliquots' => 2,
            'defaultVolume' => 5.0,
            'volumeUnit' => 'mL',
        ]);

    // Create subject and subject event
    $this->subject = Subject::factory()
        ->for($this->project)
        ->for($this->project->sites->first(), 'site')
        ->for($this->user)
        ->create([
            'subjectID' => 'TEST001',
        ]);

    // Create an arm for the project
    $this->arm = Arm::factory()
        ->for($this->project)
        ->create([
            'arm_num' => 1,
        ]);

    $this->event = Event::factory()
        ->for($this->arm)
        ->create();

    $this->subjectEvent = SubjectEvent::create([
        'subject_id' => $this->subject->id,
        'event_id' => $this->event->id,
        'status' => 3, // Status that allows logging
    ]);

    // Create PSE barcode format: project_id_subject_id_subject_event_id
    $this->pseBarcode = "{$this->project->id}_{$this->subject->id}_{$this->subjectEvent->id}";

    // Set current project in session
    Session::put('currentProject', $this->project);
});

it('can add and remove aliquots via the browser', function (): void {
    $page = visit(route('filament.project.pages.log-primary-specimens', $parameters = ['tenant' => $this->project->id]))
        ->assertSee('Scan Project-Subject-Event Barcode')
        ->type('pse_barcode', $this->pseBarcode)
        ->press('Validate Barcode');

    $page->assertSee('Aliquot 1')
        ->assertSee('Aliquot 2');

    $page->press('#addAliquot_' . $this->primarySpecimenTypes->first()->id)
        ->assertSee('Aliquot 3');

    $page->press('#removeAliquot_' . $this->primarySpecimenTypes->first()->id)
        ->assertDontSee('Aliquot 3');
});

it('requires confirmation before removing a previously logged aliquot via the browser', function (): void {

    $existingSpecimen = Specimen::factory()
        ->count(2)
        ->for($this->subjectEvent, 'subjectEvent')
        ->for($this->primarySpecimenTypes->first(), 'specimenType')
        ->for($this->project->sites->first(), 'site')
        ->sequence(
            ['barcode' => 'EX12340'],
            ['barcode' => 'EX12341'],
        )
        ->sequence(
            ['aliquot' => 1],
            ['aliquot' => 2],
        )
        ->create([
            'volume' => 3.5,
            'aliquot' => 0,
            'status' => SpecimenStatus::Logged,
            'loggedBy_id' => $this->user->id,
            'loggedAt' => now(),
        ]);

    $page = visit(route('filament.project.pages.log-primary-specimens', $parameters = ['tenant' => $this->project->id]))
        ->assertSee('Project Subject Event Barcode')
        ->fill('form.pse_barcode', $this->pseBarcode)
        ->press('Validate Barcode');

    $page->assertPresent('form.specimens.1.0.barcode')
        ->assertPresent('form.specimens.1.1.barcode');
    $page->assertValue('form.specimens.1.0.barcode', 'EX12340')
        ->assertValue('form.specimens.1.1.barcode', 'EX12341');

    $page->press('#removeAliquot_' . $this->primarySpecimenTypes->first()->id)
        ->assertSee('Are you sure you want to do this?');

    $page->press('Delete')
        ->assertPresent('form.specimens.1.0.barcode')
        ->assertNotPresent('form.specimens.1.1.barcode');
});
