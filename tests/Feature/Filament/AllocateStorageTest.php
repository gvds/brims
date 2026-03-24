<?php

use App\Enums\SpecimenStatus;
use App\Filament\Project\Resources\StorageAllocations\Pages\AllocateStorage;
use App\Filament\Project\Resources\StorageAllocations\Pages\ManageStorageAllocations;
use App\Models\Arm;
use App\Models\Event;
use App\Models\Labware;
use App\Models\Location;
use App\Models\PhysicalUnit;
use App\Models\Project;
use App\Models\Site;
use App\Models\Specimen;
use App\Models\Specimentype;
use App\Models\Subject;
use App\Models\SubjectEvent;
use App\Models\VirtualUnit;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Session;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->user = $this->adminuser;
    actingAs($this->user);

    $this->project = Project::factory()
        ->for($this->team)
        ->for($this->user, 'leader')
        ->has(Site::factory()->count(2))
        ->create();

    $this->site = $this->project->sites->first();
    $this->otherSite = $this->project->sites->last();

    $this->project->members()->attach($this->user->id, [
        'site_id' => $this->site->id,
        'role_id' => 'Admin',
    ]);

    $this->labware = Labware::factory()
        ->for($this->project)
        ->create();

    $this->subject = Subject::factory()
        ->for($this->project)
        ->for($this->site, 'site')
        ->for($this->user)
        ->create(['subjectID' => 'TEST001']);

    $this->arm = Arm::factory()
        ->for($this->project)
        ->create(['arm_num' => 1]);

    $this->event = Event::factory()
        ->for($this->arm)
        ->create();

    $this->subjectEvent = SubjectEvent::create([
        'subject_id' => $this->subject->id,
        'event_id' => $this->event->id,
        'status' => 3,
    ]);

    Session::put('currentProject', $this->project);

    Filament::setCurrentPanel('project');
    Filament::setTenant($this->project);
    Filament::bootCurrentPanel();
});

describe('AllocateStorage Page', function (): void {
    beforeEach(function (): void {
        $this->specimenDefaults = [
            'aliquot' => 1,
            'loggedBy_id' => $this->user->id,
            'loggedAt' => now(),
        ];
    });

    it('can load the page', function (): void {
        livewire(AllocateStorage::class)
            ->assertOk();
    });

    it('displays specimen types with logged specimens as checkbox options', function (): void {
        $specimenType = Specimentype::factory()
            ->for($this->project)
            ->for($this->labware, 'labware')
            ->create(['storageSpecimenType' => 'Frozen Plasma']);

        Specimen::factory()
            ->count(3)
            ->for($this->subjectEvent, 'subjectEvent')
            ->for($specimenType, 'specimenType')
            ->for($this->project)
            ->for($this->site)
            ->create([...$this->specimenDefaults, 'status' => SpecimenStatus::Logged]);

        livewire(AllocateStorage::class)
            ->assertSee($specimenType->name)
            ->assertSee('3 logged specimens');
    });

    it('does not display specimen types without storageSpecimenType', function (): void {
        $specimenType = Specimentype::factory()
            ->for($this->project)
            ->for($this->labware, 'labware')
            ->create(['storageSpecimenType' => null]);

        Specimen::factory()
            ->count(2)
            ->for($this->subjectEvent, 'subjectEvent')
            ->for($specimenType, 'specimenType')
            ->for($this->project)
            ->for($this->site)
            ->create([...$this->specimenDefaults, 'status' => SpecimenStatus::Logged]);

        livewire(AllocateStorage::class)
            ->assertDontSee($specimenType->name);
    });

    it('does not display specimen types without logged specimens', function (): void {
        $specimenType = Specimentype::factory()
            ->for($this->project)
            ->for($this->labware, 'labware')
            ->create(['storageSpecimenType' => 'Frozen Plasma']);

        Specimen::factory()
            ->count(2)
            ->for($this->subjectEvent, 'subjectEvent')
            ->for($specimenType, 'specimenType')
            ->for($this->project)
            ->for($this->site)
            ->create([...$this->specimenDefaults, 'status' => SpecimenStatus::InStorage]);

        livewire(AllocateStorage::class)
            ->assertDontSee($specimenType->name);
    });

    it('does not display specimens from other sites', function (): void {
        $specimenType = Specimentype::factory()
            ->for($this->project)
            ->for($this->labware, 'labware')
            ->create(['storageSpecimenType' => 'Frozen Plasma']);

        Specimen::factory()
            ->count(2)
            ->for($this->subjectEvent, 'subjectEvent')
            ->for($specimenType, 'specimenType')
            ->for($this->project)
            ->for($this->otherSite)
            ->create([...$this->specimenDefaults, 'status' => SpecimenStatus::Logged]);

        livewire(AllocateStorage::class)
            ->assertDontSee($specimenType->name);
    });

    it('requires at least one specimen type to be selected', function (): void {
        livewire(AllocateStorage::class)
            ->set('data.specimenTypeIds', [])
            ->call('allocate')
            ->assertHasFormErrors(['specimenTypeIds' => 'required']);
    });

    it('allocates specimens to virtual unit locations', function (): void {
        $storageType = 'Frozen Plasma';

        $specimenType = Specimentype::factory()
            ->for($this->project)
            ->for($this->labware, 'labware')
            ->create(['storageSpecimenType' => $storageType]);

        $specimens = Specimen::factory()
            ->count(3)
            ->for($this->subjectEvent, 'subjectEvent')
            ->for($specimenType, 'specimenType')
            ->for($this->project)
            ->for($this->site)
            ->create([...$this->specimenDefaults, 'status' => SpecimenStatus::Logged]);

        $physicalUnit = PhysicalUnit::factory()->create();

        $virtualUnit = VirtualUnit::factory()->create([
            'physical_unit_id' => $physicalUnit->id,
            'project_id' => $this->project->id,
            'storageSpecimenType' => $storageType,
            'active' => true,
        ]);

        foreach (range(1, 5) as $position) {
            Location::create([
                'virtual_unit_id' => $virtualUnit->id,
                'rack' => 1,
                'box' => 'A',
                'position' => $position,
            ]);
        }

        livewire(AllocateStorage::class)
            ->set('data.specimenTypeIds', [$specimenType->id])
            ->call('allocate')
            ->assertHasNoFormErrors()
            ->assertNotified();

        foreach ($specimens as $specimen) {
            expect($specimen->refresh()->status)->toBe(SpecimenStatus::InStorage);
        }

        expect(Location::where('virtual_unit_id', $virtualUnit->id)->where('used', true)->count())->toBe(3);
        expect(Location::where('virtual_unit_id', $virtualUnit->id)->where('used', false)->count())->toBe(2);
    });

    it('creates storage allocation and storage log records', function (): void {
        $storageType = 'Refrigerated Sample';

        $specimenType = Specimentype::factory()
            ->for($this->project)
            ->for($this->labware, 'labware')
            ->create(['storageSpecimenType' => $storageType]);

        Specimen::factory()
            ->count(2)
            ->for($this->subjectEvent, 'subjectEvent')
            ->for($specimenType, 'specimenType')
            ->for($this->project)
            ->for($this->site)
            ->create([...$this->specimenDefaults, 'status' => SpecimenStatus::Logged]);

        $physicalUnit = PhysicalUnit::factory()->create();

        $virtualUnit = VirtualUnit::factory()->create([
            'physical_unit_id' => $physicalUnit->id,
            'project_id' => $this->project->id,
            'storageSpecimenType' => $storageType,
            'active' => true,
        ]);

        foreach (range(1, 5) as $position) {
            Location::create([
                'virtual_unit_id' => $virtualUnit->id,
                'rack' => 1,
                'box' => 'A',
                'position' => $position,
            ]);
        }

        livewire(AllocateStorage::class)
            ->set('data.specimenTypeIds', [$specimenType->id])
            ->call('allocate')
            ->assertHasNoFormErrors();

        $this->assertDatabaseCount('storage_allocations', 1);
        $this->assertDatabaseCount('storage_logs', 2);
        $this->assertDatabaseHas('storage_logs', [
            'specimentype_id' => $specimenType->id,
        ]);
    });

    it('notifies when no virtual unit exists for storage type', function (): void {
        $specimenType = Specimentype::factory()
            ->for($this->project)
            ->for($this->labware, 'labware')
            ->create(['storageSpecimenType' => 'Missing Type']);

        Specimen::factory()
            ->for($this->subjectEvent, 'subjectEvent')
            ->for($specimenType, 'specimenType')
            ->for($this->project)
            ->for($this->site)
            ->create([...$this->specimenDefaults, 'status' => SpecimenStatus::Logged]);

        livewire(AllocateStorage::class)
            ->set('data.specimenTypeIds', [$specimenType->id])
            ->call('allocate')
            ->assertNotified();
    });

    it('notifies when insufficient storage locations exist', function (): void {
        $storageType = 'Frozen Plasma';

        $specimenType = Specimentype::factory()
            ->for($this->project)
            ->for($this->labware, 'labware')
            ->create(['storageSpecimenType' => $storageType]);

        Specimen::factory()
            ->count(5)
            ->for($this->subjectEvent, 'subjectEvent')
            ->for($specimenType, 'specimenType')
            ->for($this->project)
            ->for($this->site)
            ->create([...$this->specimenDefaults, 'status' => SpecimenStatus::Logged]);

        $physicalUnit = PhysicalUnit::factory()->create();

        $virtualUnit = VirtualUnit::factory()->create([
            'physical_unit_id' => $physicalUnit->id,
            'project_id' => $this->project->id,
            'storageSpecimenType' => $storageType,
            'active' => true,
        ]);

        foreach (range(1, 2) as $position) {
            Location::create([
                'virtual_unit_id' => $virtualUnit->id,
                'rack' => 1,
                'box' => 'A',
                'position' => $position,
            ]);
        }

        livewire(AllocateStorage::class)
            ->set('data.specimenTypeIds', [$specimenType->id])
            ->call('allocate')
            ->assertNotified();

        expect(Specimen::withoutGlobalScopes()->where('specimenType_id', $specimenType->id)->where('status', SpecimenStatus::Logged)->count())->toBe(5);
    });
});

describe('ManageStorageAllocations Page', function (): void {
    it('has an allocate storage action', function (): void {
        livewire(ManageStorageAllocations::class)
            ->assertActionExists('allocate');
    });
});
