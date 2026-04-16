<?php

use App\Filament\Admin\Resources\PhysicalUnits\Pages\ViewPhysicalUnit;
use App\Filament\Admin\Resources\PhysicalUnits\RelationManagers\VirtualUnitsRelationManager;
use App\Models\PhysicalUnit;
use App\Models\Project;
use App\Models\Section;
use App\Models\Specimentype;
use App\Models\StudyDesign;
use App\Models\Team;
use App\Models\UnitDefinition;
use App\Models\User;
use App\Models\VirtualUnit;
use Livewire\Livewire;

it('clears start and end boxes when rack capacity changes in a partial selection', function (): void {
    $team = Team::factory()->create();
    $leader = User::factory()->create(['team_id' => $team->id]);
    $user = User::factory()->create(['team_id' => $team->id]);

    $studyDesign = StudyDesign::factory()->create();

    $project = Project::factory()->create([
        'team_id' => $team->id,
        'leader_id' => $leader->id,
        'study_design_id' => $studyDesign->id,
    ]);

    $specimentype = Specimentype::factory()->create([
        'project_id' => $project->id,
        'storageSpecimenType' => fake()->word(),
        'store' => true,
    ]);

    $unitDefinition = UnitDefinition::factory()->create([
        'boxDesignation' => 'Numeric',
    ]);

    Section::factory()->create([
        'unit_definition_id' => $unitDefinition->id,
        'section_number' => 1,
        'rows' => 1,
        'columns' => 1,
        'boxes' => 10,
        'positions' => 100,
    ]);

    $physicalUnit = PhysicalUnit::factory()->create([
        'unit_definition_id' => $unitDefinition->id,
        'user_id' => $user->id,
    ]);

    VirtualUnit::factory()->create([
        'physical_unit_id' => $physicalUnit->id,
        'project_id' => $project->id,
        'storageSpecimenType' => $specimentype->storageSpecimenType,
        'rack_extent' => 'Partial',
        'startRack' => 1,
        'endRack' => 1,
        'startBox' => '1',
        'endBox' => '3',
        'rackCapacity' => 10,
        'boxCapacity' => 100,
    ]);

    $this->actingAs($user);

    Livewire::test(VirtualUnitsRelationManager::class, [
        'ownerRecord' => $physicalUnit,
        'pageClass' => ViewPhysicalUnit::class,
    ])
        ->call('initialise')
        ->set('data', ['startBox' => '1', 'endBox' => '3'])
        ->set('racks', [1 => 'p'])
        ->call('toggleRack', 1, 1)
        ->assertSet('data.startBox', null)
        ->assertSet('data.endBox', null);
});

it('can sort by racks using the startRack/endRack underlying columns', function (): void {
    $team = Team::factory()->create();
    $leader = User::factory()->create(['team_id' => $team->id]);
    $user = User::factory()->create(['team_id' => $team->id]);

    $studyDesign = StudyDesign::factory()->create();

    $project = Project::factory()->create([
        'team_id' => $team->id,
        'leader_id' => $leader->id,
        'study_design_id' => $studyDesign->id,
    ]);

    $specimentype = Specimentype::factory()->create(['project_id' => $project->id]);

    $unitDefinition = UnitDefinition::factory()->create(['boxDesignation' => 'Numeric']);

    Section::factory()->create([
        'unit_definition_id' => $unitDefinition->id,
        'section_number' => 1,
        'rows' => 1,
        'columns' => 1,
        'boxes' => 10,
        'positions' => 100,
    ]);

    $physicalUnit = PhysicalUnit::factory()->create([
        'unit_definition_id' => $unitDefinition->id,
        'user_id' => $user->id,
    ]);

    $virtualUnit1 = VirtualUnit::factory()->create([
        'physical_unit_id' => $physicalUnit->id,
        'project_id' => $project->id,
        'storageSpecimenType' => $specimentype->storageSpecimenType ?? 'Test',
        'rack_extent' => 'Full',
        'startRack' => 2,
        'endRack' => 2,
        'startBox' => '1',
        'endBox' => '10',
        'rackCapacity' => 10,
        'boxCapacity' => 100,
    ]);

    $virtualUnit2 = VirtualUnit::factory()->create([
        'physical_unit_id' => $physicalUnit->id,
        'project_id' => $project->id,
        'storageSpecimenType' => $specimentype->storageSpecimenType ?? 'Test',
        'rack_extent' => 'Full',
        'startRack' => 1,
        'endRack' => 1,
        'startBox' => '1',
        'endBox' => '10',
        'rackCapacity' => 10,
        'boxCapacity' => 100,
    ]);

    $this->actingAs($user);

    Livewire::test(VirtualUnitsRelationManager::class, [
        'ownerRecord' => $physicalUnit,
        'pageClass' => ViewPhysicalUnit::class,
    ])
        ->sortTable('Racks')
        ->assertCanSeeTableRecords([$virtualUnit2, $virtualUnit1], inOrder: true);
});
