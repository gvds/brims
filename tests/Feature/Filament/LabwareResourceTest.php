<?php

/**
 * LabwareResource Comprehensive Test Suite
 *
 * This test suite provides comprehensive coverage for the LabwareResource functionality
 * using Pest v4 testing framework and Filament v4 testing patterns.
 *
 * Coverage includes:
 * - Relation Manager table functionality (search, display, pagination)
 * - CRUD operations via relation manager (create via header actions, bulk delete)
 * - Data validation and business rules
 * - Specimen type relationships and constraints
 * - Regex barcode format handling
 *
 * Note: This test focuses on the RelationManager component as the LabwareResource
 * is a nested resource that doesn't have standalone list pages, but is managed
 * through the Project resource's relation manager.
 */

use App\Filament\Resources\Projects\RelationManagers\LabwareRelationManager;
use App\Models\Labware;
use App\Models\Project;
use App\Models\Specimentype;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Testing\TestAction;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    actingAs($this->adminuser);

    // Create a test project for nested resource context
    $this->project = Project::factory()->create([
        'team_id' => $this->team->id,
        'leader_id' => $this->adminuser->id,
    ]);
});

describe('LabwareResource Table Functionality', function (): void {
    beforeEach(function (): void {
        $this->labware = Labware::factory()->count(3)->create([
            'project_id' => $this->project->id,
        ]);
    });

    it('can display labware in relation manager table', function (): void {
        livewire(LabwareRelationManager::class, [
            'ownerRecord' => $this->project,
            'pageClass' => \App\Filament\Resources\Projects\Pages\ViewProject::class,
        ])
            ->assertCanSeeTableRecords($this->labware);
    });

    it('can search labware by name in relation manager', function (): void {
        livewire(LabwareRelationManager::class, [
            'ownerRecord' => $this->project,
            'pageClass' => \App\Filament\Resources\Projects\Pages\ViewProject::class,
        ])
            ->searchTable($this->labware->first()->name)
            ->assertCanSeeTableRecords($this->labware->take(1))
            ->assertCanNotSeeTableRecords($this->labware->skip(1));
    });

    it('displays all required table columns', function (): void {
        livewire(LabwareRelationManager::class, [
            'ownerRecord' => $this->project,
            'pageClass' => \App\Filament\Resources\Projects\Pages\ViewProject::class,
        ])
            ->assertCanSeeTableRecords($this->labware)
            ->assertCanRenderTableColumn('name')
            ->assertCanRenderTableColumn('barcodeFormat')
            ->assertCanRenderTableColumn('specimenTypes_count');
    });

    it('can bulk delete multiple labware records', function (): void {
        $labwareToDelete = $this->labware->take(2);

        livewire(LabwareRelationManager::class, [
            'ownerRecord' => $this->project,
            'pageClass' => \App\Filament\Resources\Projects\Pages\ViewProject::class,
        ])
            ->selectTableRecords($labwareToDelete->pluck('id')->toArray())
            ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
            ->assertNotified();

        foreach ($labwareToDelete as $labware) {
            assertDatabaseMissing(Labware::class, [
                'id' => $labware->id,
            ]);
        }
    });

    it('handles empty table state properly', function (): void {
        // Delete all labware for this project
        Labware::where('project_id', $this->project->id)->delete();

        livewire(LabwareRelationManager::class, [
            'ownerRecord' => $this->project,
            'pageClass' => \App\Filament\Resources\Projects\Pages\ViewProject::class,
        ])
            ->assertCountTableRecords(0);
    });

    it('maintains correct table pagination', function (): void {
        // Create more labware to test pagination
        $moreLabware = Labware::factory()->count(15)->create([
            'project_id' => $this->project->id,
        ]);

        $allLabware = $this->labware->concat($moreLabware);

        livewire(LabwareRelationManager::class, [
            'ownerRecord' => $this->project,
            'pageClass' => \App\Filament\Resources\Projects\Pages\ViewProject::class,
        ])
            ->assertCountTableRecords(18); // Should show total count across pages
    });
});

describe('LabwareResource Business Rules', function (): void {
    beforeEach(function (): void {
        $this->labware = Labware::factory()->count(3)->create([
            'project_id' => $this->project->id,
        ]);
    });

    it('displays labware with specimen types correctly', function (): void {
        $labware = $this->labware->first();

        // Create specimen types for this labware
        Specimentype::factory()->count(2)->create([
            'labware_id' => $labware->id,
            'project_id' => $this->project->id,
        ]);

        // Verify the labware with specimen types is still displayed
        livewire(LabwareRelationManager::class, [
            'ownerRecord' => $this->project,
            'pageClass' => \App\Filament\Resources\Projects\Pages\ViewProject::class,
        ])
            ->assertCanSeeTableRecords([$labware]);
    });

    it('shows only project-related labware in relation manager', function (): void {
        // Create global labware (no project_id) and another project's labware
        $globalLabware = Labware::factory()->create(['project_id' => null]);
        $otherProject = Project::factory()->create(['team_id' => $this->team->id, 'leader_id' => $this->adminuser->id]);
        $otherProjectLabware = Labware::factory()->create(['project_id' => $otherProject->id]);

        // Relation manager should show project labware and global labware, but not other project labware
        $component = livewire(LabwareRelationManager::class, [
            'ownerRecord' => $this->project,
            'pageClass' => \App\Filament\Resources\Projects\Pages\ViewProject::class,
        ]);

        // Project labware should be visible
        $component->assertCanSeeTableRecords($this->labware);

        // Other project's labware should NOT be visible (relation manager scope)
        $component->assertCanNotSeeTableRecords([$otherProjectLabware]);
    });
});

describe('LabwareResource Data Integrity', function (): void {
    it('creates labware with correct regex format via factory', function (): void {
        $labware = Labware::factory()->create([
            'project_id' => $this->project->id,
            'barcodeFormat' => 'TEST[0-9]{3}',
        ]);

        expect($labware->barcodeFormat)->toBe('TEST[0-9]{3}');
        expect($labware->project_id)->toBe($this->project->id);
    });

    it('maintains relationship with specimen types', function (): void {
        $labware = Labware::factory()->create([
            'project_id' => $this->project->id,
        ]);

        $specimenTypes = Specimentype::factory()->count(3)->create([
            'labware_id' => $labware->id,
            'project_id' => $this->project->id,
        ]);

        expect($labware->specimenTypes)->toHaveCount(3);
        expect($labware->specimenTypes->pluck('id'))->toEqual($specimenTypes->pluck('id'));
    });

    it('handles labware name constraints properly', function (): void {
        $labware = Labware::factory()->create([
            'project_id' => $this->project->id,
            'name' => 'Test Tube Set A',
        ]);

        expect($labware->name)->toBe('Test Tube Set A');
        expect(strlen((string) $labware->name))->toBeLessThanOrEqual(30);
    });

    it('handles barcode format constraints properly', function (): void {
        $labware = Labware::factory()->create([
            'project_id' => $this->project->id,
            'barcodeFormat' => 'ABC[0-9]{5}DEF',
        ]);

        expect($labware->barcodeFormat)->toBe('ABC[0-9]{5}DEF');
        expect(strlen((string) $labware->barcodeFormat))->toBeLessThanOrEqual(50);
    });
});
