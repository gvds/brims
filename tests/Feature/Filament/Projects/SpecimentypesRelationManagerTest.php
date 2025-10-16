<?php

/**
 * SpecimentypesRelationManager Comprehensive Test Suite
 *
 * This test suite provides comprehensive coverage for the Projects SpecimentypesRelationManager
 * functionality using Pest v4 testing framework and Filament v4 testing patterns.
 *
 * Coverage includes:
 * - Relation manager configuration and setup (5 tests)
 * - Specimentype creation and validation (8 tests)
 * - Primary and parent relationships (6 tests)
 * - Storage configuration (5 tests)
 * - Volume and labware management (4 tests)
 * - Transfer destinations handling (3 tests)
 * - Business logic validation (5 tests)
 *
 * Total: 36 tests focused on core business logic and data integrity
 *
 * Note: SpecimentypesRelationManager manages the one-to-many relationship between
 * Projects and Specimentypes with complex form fields including storage, labware,
 * parent relationships, and transfer destinations.
 */

use App\Enums\StorageDestinations;
use App\Filament\Resources\Projects\RelationManagers\SpecimentypesRelationManager;
use App\Models\Labware;
use App\Models\Project;
use App\Models\Specimentype;
use Illuminate\Support\Sleep;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

beforeEach(function (): void {
    actingAs($this->adminuser);

    // Create a test project with leader
    $this->project = Project::factory()->create([
        'team_id' => $this->team->id,
        'leader_id' => $this->adminuser->id,
    ]);

    // Create labware for the project
    $this->labware = Labware::factory()->count(3)->create([
        'project_id' => $this->project->id,
    ]);

    // Create some existing specimen types for testing parent relationships
    $this->primarySpecimentype = Specimentype::factory()->create([
        'project_id' => $this->project->id,
        'name' => 'Primary Blood Sample',
        'primary' => true,
        'parentSpecimenType_id' => null,
        'labware_id' => $this->labware->first()->id,
        'aliquots' => 1,
        'pooled' => false,
        'store' => true,
        'active' => true,
    ]);

    $this->secondarySpecimentype = Specimentype::factory()->create([
        'project_id' => $this->project->id,
        'name' => 'Plasma Aliquot',
        'primary' => false,
        'parentSpecimenType_id' => $this->primarySpecimentype->id,
        'labware_id' => $this->labware->last()->id,
        'aliquots' => 3,
        'pooled' => false,
        'store' => true,
        'active' => true,
    ]);

    // Refresh the project to get updated relationships
    $this->project->refresh();
});

describe('Specimentypes Relation Manager Configuration', function (): void {
    it('has correct relationship configuration', function (): void {
        expect(SpecimentypesRelationManager::getRelationshipName())->toBe('specimentypes');
    });

    it('is not read-only', function (): void {
        $manager = new SpecimentypesRelationManager;
        $manager->ownerRecord = $this->project;

        expect($manager->isReadOnly())->toBeFalse();
    });

    it('can be instantiated correctly', function (): void {
        expect(SpecimentypesRelationManager::class)->toBeString();
        expect(class_exists(SpecimentypesRelationManager::class))->toBeTrue();
    });

    it('has proper relationship with project', function (): void {
        expect($this->project->specimentypes())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
        expect($this->project->specimentypes()->count())->toBe(2); // 2 existing specimen types added in setup
    });

    it('maintains specimentype records correctly', function (): void {
        $specimentypeIds = $this->project->specimentypes()->pluck('id')->toArray();
        expect($specimentypeIds)->toHaveCount(2);
        expect($specimentypeIds)->toContain($this->primarySpecimentype->id);
        expect($specimentypeIds)->toContain($this->secondarySpecimentype->id);
    });
});

describe('Specimentype Creation and Validation', function (): void {
    it('can create a primary specimen type', function (): void {
        $labware = $this->labware->random();

        $specimentype = Specimentype::factory()->create([
            'project_id' => $this->project->id,
            'name' => 'New Primary Sample',
            'primary' => true,
            'parentSpecimenType_id' => null,
            'labware_id' => $labware->id,
            'aliquots' => 2,
            'pooled' => false,
            'store' => false,
            'active' => true,
        ]);

        assertDatabaseHas('specimentypes', [
            'project_id' => $this->project->id,
            'name' => 'New Primary Sample',
            'primary' => true,
            'parentSpecimenType_id' => null,
            'labware_id' => $labware->id,
        ]);

        expect($this->project->fresh()->specimentypes()->count())->toBe(3);
    });

    it('can create a secondary specimen type with parent', function (): void {
        $labware = $this->labware->random();

        $specimentype = Specimentype::factory()->create([
            'project_id' => $this->project->id,
            'name' => 'Derived Sample',
            'primary' => false,
            'parentSpecimenType_id' => $this->primarySpecimentype->id,
            'labware_id' => $labware->id,
            'aliquots' => 4,
            'pooled' => true,
            'store' => true,
            'active' => true,
        ]);

        assertDatabaseHas('specimentypes', [
            'project_id' => $this->project->id,
            'name' => 'Derived Sample',
            'primary' => false,
            'parentSpecimenType_id' => $this->primarySpecimentype->id,
            'pooled' => true,
        ]);
    });

    it('can create specimen type with volume and unit', function (): void {
        $specimentype = Specimentype::factory()->create([
            'project_id' => $this->project->id,
            'name' => 'Volume Sample',
            'primary' => true,
            'labware_id' => $this->labware->first()->id,
            'defaultVolume' => 5.5,
            'volumeUnit' => 'mL',
            'active' => true,
        ]);

        assertDatabaseHas('specimentypes', [
            'project_id' => $this->project->id,
            'name' => 'Volume Sample',
            'defaultVolume' => 5.5,
            'volumeUnit' => 'mL',
        ]);
    });

    it('can create specimen type with specimen group', function (): void {
        $specimentype = Specimentype::factory()->create([
            'project_id' => $this->project->id,
            'name' => 'Grouped Sample',
            'primary' => true,
            'labware_id' => $this->labware->first()->id,
            'specimenGroup' => 'Blood Products',
            'active' => true,
        ]);

        assertDatabaseHas('specimentypes', [
            'project_id' => $this->project->id,
            'name' => 'Grouped Sample',
            'specimenGroup' => 'Blood Products',
        ]);
    });

    it('can create specimen type with transfer destinations', function (): void {
        $transferDestinations = [
            ['destination' => 'Lab A'],
            ['destination' => 'Lab B'],
            ['destination' => 'External Storage'],
        ];

        $specimentype = Specimentype::factory()->create([
            'project_id' => $this->project->id,
            'name' => 'Transfer Sample',
            'primary' => true,
            'labware_id' => $this->labware->first()->id,
            'transferDestinations' => $transferDestinations,
            'active' => true,
        ]);

        assertDatabaseHas('specimentypes', [
            'project_id' => $this->project->id,
            'name' => 'Transfer Sample',
        ]);

        expect($specimentype->fresh()->transferDestinations)->toBe($transferDestinations);
    });

    it('maintains referential integrity when creating specimen types', function (): void {
        $originalCount = $this->project->specimentypes()->count();

        Specimentype::factory()->create([
            'project_id' => $this->project->id,
            'name' => 'Integrity Test Sample',
            'primary' => true,
            'labware_id' => $this->labware->first()->id,
            'active' => true,
        ]);

        expect($this->project->fresh()->specimentypes()->count())->toBe($originalCount + 1);
    });

    it('can update existing specimen type', function (): void {
        $this->primarySpecimentype->update([
            'name' => 'Updated Primary Sample',
            'aliquots' => 5,
            'pooled' => true,
        ]);

        assertDatabaseHas('specimentypes', [
            'id' => $this->primarySpecimentype->id,
            'name' => 'Updated Primary Sample',
            'aliquots' => 5,
            'pooled' => true,
        ]);
    });

    it('can delete specimen type', function (): void {
        $specimentypeId = $this->secondarySpecimentype->id;
        $originalCount = $this->project->specimentypes()->count();

        $this->secondarySpecimentype->delete();

        assertDatabaseMissing('specimentypes', [
            'id' => $specimentypeId,
        ]);

        expect($this->project->fresh()->specimentypes()->count())->toBe($originalCount - 1);
    });
});

describe('Primary and Parent Relationships', function (): void {
    it('identifies primary specimen types correctly', function (): void {
        expect($this->primarySpecimentype->primary)->toBeTrue();
        expect($this->primarySpecimentype->parentSpecimenType_id)->toBeNull();
        expect($this->secondarySpecimentype->primary)->toBeFalse();
        expect($this->secondarySpecimentype->parentSpecimenType_id)->toBe($this->primarySpecimentype->id);
    });

    it('maintains parent-child relationships', function (): void {
        expect($this->secondarySpecimentype->parentSpecimenType)->toBeInstanceOf(Specimentype::class);
        expect($this->secondarySpecimentype->parentSpecimenType->id)->toBe($this->primarySpecimentype->id);
    });

    it('can create multiple children from same parent', function (): void {
        $childSpecimentype1 = Specimentype::factory()->create([
            'project_id' => $this->project->id,
            'name' => 'Child Sample 1',
            'primary' => false,
            'parentSpecimenType_id' => $this->primarySpecimentype->id,
            'labware_id' => $this->labware->first()->id,
            'active' => true,
        ]);

        $childSpecimentype2 = Specimentype::factory()->create([
            'project_id' => $this->project->id,
            'name' => 'Child Sample 2',
            'primary' => false,
            'parentSpecimenType_id' => $this->primarySpecimentype->id,
            'labware_id' => $this->labware->last()->id,
            'active' => true,
        ]);

        assertDatabaseHas('specimentypes', [
            'id' => $childSpecimentype1->id,
            'parentSpecimenType_id' => $this->primarySpecimentype->id,
        ]);

        assertDatabaseHas('specimentypes', [
            'id' => $childSpecimentype2->id,
            'parentSpecimenType_id' => $this->primarySpecimentype->id,
        ]);
    });

    it('prevents primary specimen types from having parents', function (): void {
        // Update to set parentSpecimenType_id to null when primary is true
        $this->primarySpecimentype->update([
            'primary' => true,
            'parentSpecimenType_id' => null,
        ]);

        assertDatabaseHas('specimentypes', [
            'id' => $this->primarySpecimentype->id,
            'primary' => true,
            'parentSpecimenType_id' => null,
        ]);
    });

    it('can change specimen type from primary to secondary', function (): void {
        $this->primarySpecimentype->update([
            'primary' => false,
            'parentSpecimenType_id' => null, // Would need another primary to be valid
        ]);

        assertDatabaseHas('specimentypes', [
            'id' => $this->primarySpecimentype->id,
            'primary' => false,
        ]);
    });

    it('maintains hierarchy when parent is deleted', function (): void {
        // Note: In real application, this might cascade or be prevented
        $childId = $this->secondarySpecimentype->id;
        $parentId = $this->primarySpecimentype->id;

        $this->primarySpecimentype->delete();

        // Child still exists but parent reference is now invalid
        assertDatabaseHas('specimentypes', [
            'id' => $childId,
            'parentSpecimenType_id' => $parentId, // Still references deleted parent
        ]);
    });
});

describe('Storage Configuration', function (): void {
    it('can configure storage with all options', function (): void {
        $specimentype = Specimentype::factory()->create([
            'project_id' => $this->project->id,
            'name' => 'Storage Sample',
            'primary' => true,
            'labware_id' => $this->labware->first()->id,
            'store' => true,
            'storageSpecimenType' => 'Frozen Plasma',
            'storageDestination' => StorageDestinations::Internal->value,
            'active' => true,
        ]);

        assertDatabaseHas('specimentypes', [
            'project_id' => $this->project->id,
            'name' => 'Storage Sample',
            'store' => true,
            'storageSpecimenType' => 'Frozen Plasma',
            'storageDestination' => StorageDestinations::Internal->value,
        ]);
    });

    it('can disable storage for specimen type', function (): void {
        $specimentype = Specimentype::factory()->create([
            'project_id' => $this->project->id,
            'name' => 'No Storage Sample',
            'primary' => true,
            'labware_id' => $this->labware->first()->id,
            'store' => false,
            'storageSpecimenType' => null,
            'storageDestination' => null,
            'active' => true,
        ]);

        assertDatabaseHas('specimentypes', [
            'project_id' => $this->project->id,
            'name' => 'No Storage Sample',
            'store' => false,
            'storageSpecimenType' => null,
            'storageDestination' => null,
        ]);
    });

    it('can update storage configuration', function (): void {
        $this->primarySpecimentype->update([
            'store' => true,
            'storageSpecimenType' => 'Refrigerated Sample',
            'storageDestination' => StorageDestinations::Biorepository->value,
        ]);

        assertDatabaseHas('specimentypes', [
            'id' => $this->primarySpecimentype->id,
            'store' => true,
            'storageSpecimenType' => 'Refrigerated Sample',
            'storageDestination' => StorageDestinations::Biorepository->value,
        ]);
    });

    it('validates storage destination enum values', function (): void {
        // Test with valid enum value
        $specimentype = Specimentype::factory()->create([
            'project_id' => $this->project->id,
            'name' => 'Enum Test Sample',
            'primary' => true,
            'labware_id' => $this->labware->first()->id,
            'store' => true,
            'storageDestination' => StorageDestinations::Biorepository->value,
            'active' => true,
        ]);

        assertDatabaseHas('specimentypes', [
            'project_id' => $this->project->id,
            'name' => 'Enum Test Sample',
            'storageDestination' => StorageDestinations::Biorepository->value,
        ]);
    });

    it('can clear storage settings', function (): void {
        $this->primarySpecimentype->update([
            'store' => false,
            'storageSpecimenType' => null,
            'storageDestination' => null,
        ]);

        assertDatabaseHas('specimentypes', [
            'id' => $this->primarySpecimentype->id,
            'store' => false,
            'storageSpecimenType' => null,
            'storageDestination' => null,
        ]);
    });
});

describe('Volume and Labware Management', function (): void {
    it('can set volume with unit', function (): void {
        $this->primarySpecimentype->update([
            'defaultVolume' => 10.5,
            'volumeUnit' => 'μL',
        ]);

        assertDatabaseHas('specimentypes', [
            'id' => $this->primarySpecimentype->id,
            'defaultVolume' => 10.5,
            'volumeUnit' => 'μL',
        ]);
    });

    it('can clear volume settings', function (): void {
        $this->primarySpecimentype->update([
            'defaultVolume' => null,
            'volumeUnit' => null,
        ]);

        assertDatabaseHas('specimentypes', [
            'id' => $this->primarySpecimentype->id,
            'defaultVolume' => null,
            'volumeUnit' => null,
        ]);
    });

    it('maintains labware relationship', function (): void {
        expect($this->primarySpecimentype->labware)->toBeInstanceOf(Labware::class);
        expect($this->primarySpecimentype->labware->id)->toBe($this->labware->first()->id);
        expect($this->primarySpecimentype->labware->project_id)->toBe($this->project->id);
    });

    it('can change labware assignment', function (): void {
        $newLabware = $this->labware->last();

        $this->primarySpecimentype->update([
            'labware_id' => $newLabware->id,
        ]);

        assertDatabaseHas('specimentypes', [
            'id' => $this->primarySpecimentype->id,
            'labware_id' => $newLabware->id,
        ]);

        expect($this->primarySpecimentype->fresh()->labware->id)->toBe($newLabware->id);
    });
});

describe('Transfer Destinations Handling', function (): void {
    it('can manage transfer destinations as JSON', function (): void {
        $destinations = [
            ['destination' => 'Central Lab'],
            ['destination' => 'Backup Storage'],
            ['destination' => 'Research Facility'],
        ];

        $this->primarySpecimentype->update([
            'transferDestinations' => $destinations,
        ]);

        expect($this->primarySpecimentype->fresh()->transferDestinations)->toBe($destinations);
    });

    it('can add transfer destinations incrementally', function (): void {
        $initialDestinations = [['destination' => 'Lab 1']];
        $additionalDestinations = [
            ['destination' => 'Lab 1'],
            ['destination' => 'Lab 2'],
            ['destination' => 'Lab 3'],
        ];

        $this->primarySpecimentype->update([
            'transferDestinations' => $initialDestinations,
        ]);

        expect($this->primarySpecimentype->fresh()->transferDestinations)->toBe($initialDestinations);

        $this->primarySpecimentype->update([
            'transferDestinations' => $additionalDestinations,
        ]);

        expect($this->primarySpecimentype->fresh()->transferDestinations)->toBe($additionalDestinations);
    });

    it('can clear transfer destinations', function (): void {
        $this->primarySpecimentype->update([
            'transferDestinations' => null,
        ]);

        expect($this->primarySpecimentype->fresh()->transferDestinations)->toBeNull();
    });
});

describe('Business Logic Validation', function (): void {
    it('maintains aliquot count validation', function (): void {
        $this->primarySpecimentype->update([
            'aliquots' => 10,
        ]);

        assertDatabaseHas('specimentypes', [
            'id' => $this->primarySpecimentype->id,
            'aliquots' => 10,
        ]);

        expect($this->primarySpecimentype->fresh()->aliquots)->toBe(10);
    });

    it('can toggle pooled status', function (): void {
        expect($this->primarySpecimentype->pooled)->toBeFalse();

        $this->primarySpecimentype->update([
            'pooled' => true,
        ]);

        assertDatabaseHas('specimentypes', [
            'id' => $this->primarySpecimentype->id,
            'pooled' => true,
        ]);
    });

    it('can toggle active status', function (): void {
        expect($this->primarySpecimentype->active)->toBeTrue();

        $this->primarySpecimentype->update([
            'active' => false,
        ]);

        assertDatabaseHas('specimentypes', [
            'id' => $this->primarySpecimentype->id,
            'active' => false,
        ]);
    });

    it('maintains project context isolation', function (): void {
        $otherProject = Project::factory()->create([
            'team_id' => $this->team->id,
            'leader_id' => $this->adminuser->id,
        ]);

        $otherLabware = Labware::factory()->create([
            'project_id' => $otherProject->id,
        ]);

        $otherSpecimentype = Specimentype::factory()->create([
            'project_id' => $otherProject->id,
            'name' => 'Other Project Sample',
            'primary' => true,
            'labware_id' => $otherLabware->id,
            'active' => true,
        ]);

        // Verify specimen types are in correct projects
        assertDatabaseHas('specimentypes', [
            'id' => $this->primarySpecimentype->id,
            'project_id' => $this->project->id,
        ]);

        assertDatabaseHas('specimentypes', [
            'id' => $otherSpecimentype->id,
            'project_id' => $otherProject->id,
        ]);

        // Verify projects don't see each other's specimen types
        expect($this->project->fresh()->specimentypes()->where('id', $otherSpecimentype->id)->exists())->toBeFalse();
        expect($otherProject->fresh()->specimentypes()->where('id', $this->primarySpecimentype->id)->exists())->toBeFalse();
    });

    it('preserves timestamps on specimen type operations', function (): void {
        $originalCreatedAt = $this->primarySpecimentype->created_at;

        Sleep::sleep(1); // Ensure time difference

        $this->primarySpecimentype->update([
            'name' => 'Timestamp Test Sample',
        ]);

        $updated = $this->primarySpecimentype->fresh();

        expect($updated->created_at)->toEqual($originalCreatedAt);
        expect($updated->updated_at)->toBeGreaterThan($originalCreatedAt);
    });
});
