<?php

/**
 * AssaysRelationManager Comprehensive Test Suite
 *
 * This test suite provides comprehensive coverage for the Studies AssaysRelationManager
 * functionality using Pest v4 testing framework and Filament v4 testing patterns.
 *
 * Coverage includes:
 * - Relation manager configuration and setup (5 tests)
 * - Assay creation and validation (10 tests)
 * - Dynamic field management (6 tests)
 * - File management (4 tests)
 * - AssayDefinition integration (5 tests)
 * - Business logic validation (5 tests)
 *
 * Total: 35 tests focused on core business logic and data integrity
 *
 * Note: AssaysRelationManager manages the one-to-many relationship between
 * Studies and Assays with complex dynamic fields based on AssayDefinition,
 * file management, and technology platform tracking for research assays.
 */

use App\Filament\Resources\Projects\Resources\Studies\RelationManagers\AssaysRelationManager;
use App\Models\Assay;
use App\Models\AssayDefinition;
use App\Models\Project;
use App\Models\Study;
use App\Models\User;

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

    // Set the current project in session to work with StudyScope
    session()->put('currentProject', $this->project);

    // Create a test study for the project
    $this->study = Study::factory()->create([
        'project_id' => $this->project->id,
        'identifier' => 'TEST_STUDY_001',
        'title' => 'Test Study for Assays',
    ]);

    // Create an assay definition
    $this->assayDefinition = AssayDefinition::factory()->create([
        'name' => 'Test Assay Definition',
        'user_id' => $this->adminuser->id,
        'team_id' => $this->team->id,
        'active' => true,
    ]);

    // Create some existing assays for testing
    $this->existingAssay = Assay::factory()->create([
        'study_id' => $this->study->id,
        'assaydefinition_id' => $this->assayDefinition->id,
        'user_id' => $this->adminuser->id,
        'name' => 'Existing Assay',
        'technologyPlatform' => 'PCR',
    ]);

    // Create the relation manager instance
    $this->relationManager = new AssaysRelationManager;
    $this->relationManager->ownerRecord = $this->study;
});

describe('Assays Relation Manager Configuration', function (): void {
    it('has correct relationship configuration', function (): void {
        $reflection = new ReflectionClass($this->relationManager);
        $relationshipProperty = $reflection->getProperty('relationship');

        expect($relationshipProperty->getDefaultValue())->toBe('assays');
    });

    it('is not read-only', function (): void {
        expect($this->relationManager->isReadOnly())->toBeFalse();
    });

    it('can be instantiated correctly', function (): void {
        expect($this->relationManager)->toBeInstanceOf(AssaysRelationManager::class);
        expect($this->relationManager->ownerRecord)->toBeInstanceOf(Study::class);
    });

    it('has proper relationship with study', function (): void {
        $assays = $this->study->assays();

        expect($assays)->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\HasMany::class);
        expect($assays->getRelated())->toBeInstanceOf(Assay::class);
    });

    it('maintains assay records correctly', function (): void {
        $assayCount = $this->study->assays()->count();

        expect($assayCount)->toBeGreaterThan(0);
        expect($this->study->assays()->first())->toBeInstanceOf(Assay::class);
    });
});

describe('Assay Creation and Validation', function (): void {
    it('can create a basic assay', function (): void {
        $assay = Assay::factory()->create([
            'study_id' => $this->study->id,
            'assaydefinition_id' => $this->assayDefinition->id,
            'user_id' => $this->adminuser->id,
            'name' => 'Test Assay Creation',
            'technologyPlatform' => 'ELISA',
        ]);

        expect($assay->study_id)->toBe($this->study->id);
        expect($assay->assaydefinition_id)->toBe($this->assayDefinition->id);
        expect($assay->user_id)->toBe($this->adminuser->id);
        expect($assay->name)->toBe('Test Assay Creation');
        expect($assay->technologyPlatform)->toBe('ELISA');

        assertDatabaseHas('assays', [
            'id' => $assay->id,
            'study_id' => $this->study->id,
            'name' => 'Test Assay Creation',
        ]);
    });

    it('can create assay with URI', function (): void {
        $assay = Assay::factory()->create([
            'study_id' => $this->study->id,
            'assaydefinition_id' => $this->assayDefinition->id,
            'user_id' => $this->adminuser->id,
            'name' => 'Assay with URI',
            'technologyPlatform' => 'Luminex',
            'uri' => 'https://example.com/assay/123',
        ]);

        expect($assay->uri)->toBe('https://example.com/assay/123');
        assertDatabaseHas('assays', [
            'id' => $assay->id,
            'uri' => 'https://example.com/assay/123',
        ]);
    });

    it('can create assay with location', function (): void {
        $assay = Assay::factory()->create([
            'study_id' => $this->study->id,
            'assaydefinition_id' => $this->assayDefinition->id,
            'user_id' => $this->adminuser->id,
            'name' => 'Assay with Location',
            'technologyPlatform' => 'scRNAseq',
            'location' => 'Lab A, Building 2',
        ]);

        expect($assay->location)->toBe('Lab A, Building 2');
        assertDatabaseHas('assays', [
            'id' => $assay->id,
            'location' => 'Lab A, Building 2',
        ]);
    });

    it('can create assay with additional fields', function (): void {
        $additionalFields = [
            'specimen_type' => 'blood',
            'concentration' => '100',
            'quality_score' => 'high',
        ];

        $assay = Assay::factory()->create([
            'study_id' => $this->study->id,
            'assaydefinition_id' => $this->assayDefinition->id,
            'user_id' => $this->adminuser->id,
            'name' => 'Assay with Additional Fields',
            'technologyPlatform' => 'PCR',
            'additional_fields' => $additionalFields,
        ]);

        expect($assay->additional_fields)->toBe($additionalFields);
        assertDatabaseHas('assays', [
            'id' => $assay->id,
            'additional_fields' => json_encode($additionalFields),
        ]);
    });

    it('maintains referential integrity when creating assays', function (): void {
        $assay = Assay::factory()->create([
            'study_id' => $this->study->id,
            'assaydefinition_id' => $this->assayDefinition->id,
            'user_id' => $this->adminuser->id,
            'name' => 'Referential Integrity Test',
            'technologyPlatform' => 'ELISA',
        ]);

        // Verify the assay belongs to the correct study
        expect($assay->study->id)->toBe($this->study->id);
        expect($assay->assaydefinition->id)->toBe($this->assayDefinition->id);
        expect($assay->created_by->id)->toBe($this->adminuser->id);

        $this->study->refresh();
        expect($this->study->assays()->where('id', $assay->id)->exists())->toBeTrue();
    });

    it('can update existing assay', function (): void {
        $originalName = $this->existingAssay->name;
        $newName = 'Updated Assay Name';

        $this->existingAssay->update(['name' => $newName]);
        $this->existingAssay->refresh();

        expect($this->existingAssay->name)->toBe($newName);
        expect($this->existingAssay->name)->not()->toBe($originalName);

        assertDatabaseHas('assays', [
            'id' => $this->existingAssay->id,
            'name' => $newName,
        ]);
    });

    it('can delete assay', function (): void {
        $assayId = $this->existingAssay->id;

        $this->existingAssay->delete();

        assertDatabaseMissing('assays', [
            'id' => $assayId,
        ]);
    });

    it('validates required fields', function (): void {
        // Name is required
        expect(function (): void {
            Assay::factory()->create([
                'study_id' => $this->study->id,
                'assaydefinition_id' => $this->assayDefinition->id,
                'user_id' => $this->adminuser->id,
                'name' => null,
                'technologyPlatform' => 'PCR',
            ]);
        })->toThrow(Exception::class);

        // TechnologyPlatform is required
        expect(function (): void {
            Assay::factory()->create([
                'study_id' => $this->study->id,
                'assaydefinition_id' => $this->assayDefinition->id,
                'user_id' => $this->adminuser->id,
                'name' => 'Test Assay',
                'technologyPlatform' => null,
            ]);
        })->toThrow(Exception::class);
    });

    it('can create assay with multiple technology platforms', function (): void {
        $platforms = ['PCR', 'ELISA', 'scRNAseq', 'Luminex', 'Mass Spectrometry'];

        foreach ($platforms as $platform) {
            $assay = Assay::factory()->create([
                'study_id' => $this->study->id,
                'assaydefinition_id' => $this->assayDefinition->id,
                'user_id' => $this->adminuser->id,
                'name' => "Assay for {$platform}",
                'technologyPlatform' => $platform,
            ]);

            expect($assay->technologyPlatform)->toBe($platform);
        }
    });

    it('preserves timestamps on assay operations', function (): void {
        $beforeCreation = now();

        $assay = Assay::factory()->create([
            'study_id' => $this->study->id,
            'assaydefinition_id' => $this->assayDefinition->id,
            'user_id' => $this->adminuser->id,
            'name' => 'Timestamp Test Assay',
            'technologyPlatform' => 'PCR',
        ]);

        $afterCreation = now();

        expect($assay->created_at)->toBeGreaterThanOrEqual($beforeCreation->subSecond());
        expect($assay->created_at)->toBeLessThanOrEqual($afterCreation->addSecond());
        expect($assay->updated_at)->toBeGreaterThanOrEqual($beforeCreation->subSecond());
        expect($assay->updated_at)->toBeLessThanOrEqual($afterCreation->addSecond());
    });
});

describe('Dynamic Field Management', function (): void {
    it('handles JSON additional fields correctly', function (): void {
        $complexFields = [
            'experiment_date' => '2025-06-15',
            'specimen_count' => 50,
            'protocol_version' => '2.1',
            'quality_control' => ['passed' => true, 'score' => 95],
            'tags' => ['urgent', 'high-priority', 'validated'],
        ];

        $assay = Assay::factory()->create([
            'study_id' => $this->study->id,
            'assaydefinition_id' => $this->assayDefinition->id,
            'user_id' => $this->adminuser->id,
            'name' => 'Complex Fields Assay',
            'technologyPlatform' => 'PCR',
            'additional_fields' => $complexFields,
        ]);

        expect($assay->additional_fields)->toBe($complexFields);
        expect($assay->additional_fields['quality_control']['score'])->toBe(95);
        expect($assay->additional_fields['tags'])->toContain('urgent');
    });

    it('can update additional fields', function (): void {
        $originalFields = ['field1' => 'value1', 'field2' => 'value2'];
        $updatedFields = ['field1' => 'updated_value1', 'field3' => 'value3'];

        $this->existingAssay->update(['additional_fields' => $originalFields]);
        expect($this->existingAssay->additional_fields)->toBe($originalFields);

        $this->existingAssay->update(['additional_fields' => $updatedFields]);
        $this->existingAssay->refresh();

        expect($this->existingAssay->additional_fields)->toBe($updatedFields);
        expect($this->existingAssay->additional_fields['field1'])->toBe('updated_value1');
        expect($this->existingAssay->additional_fields)->not()->toHaveKey('field2');
    });

    it('handles null additional fields', function (): void {
        $assay = Assay::factory()->create([
            'study_id' => $this->study->id,
            'assaydefinition_id' => $this->assayDefinition->id,
            'user_id' => $this->adminuser->id,
            'name' => 'Null Fields Assay',
            'technologyPlatform' => 'ELISA',
            'additional_fields' => null,
        ]);

        expect($assay->additional_fields)->toBeNull();
    });

    it('can clear additional fields', function (): void {
        $this->existingAssay->update([
            'additional_fields' => ['temp_field' => 'temp_value'],
        ]);

        expect($this->existingAssay->additional_fields)->not()->toBeNull();

        $this->existingAssay->update(['additional_fields' => null]);
        $this->existingAssay->refresh();

        expect($this->existingAssay->additional_fields)->toBeNull();
    });

    it('validates JSON structure for additional fields', function (): void {
        $assay = Assay::factory()->create([
            'study_id' => $this->study->id,
            'assaydefinition_id' => $this->assayDefinition->id,
            'user_id' => $this->adminuser->id,
            'name' => 'JSON Validation Assay',
            'technologyPlatform' => 'Luminex',
            'additional_fields' => ['valid' => 'json', 'structure' => true],
        ]);

        // Verify the JSON is stored and retrieved correctly
        $fresh = $assay->fresh();
        expect($fresh->additional_fields)->toBeArray();
        expect($fresh->additional_fields['valid'])->toBe('json');
        expect($fresh->additional_fields['structure'])->toBe(true);
    });

    it('preserves field types in additional fields', function (): void {
        $mixedFields = [
            'string_field' => 'text_value',
            'integer_field' => 42,
            'float_field' => 3.14159,
            'boolean_field' => true,
            'null_field' => null,
            'array_field' => [1, 2, 3],
            'object_field' => ['nested' => 'value'],
        ];

        $assay = Assay::factory()->create([
            'study_id' => $this->study->id,
            'assaydefinition_id' => $this->assayDefinition->id,
            'user_id' => $this->adminuser->id,
            'name' => 'Mixed Types Assay',
            'technologyPlatform' => 'scRNAseq',
            'additional_fields' => $mixedFields,
        ]);

        $fresh = $assay->fresh();
        expect($fresh->additional_fields['string_field'])->toBeString();
        expect($fresh->additional_fields['integer_field'])->toBeInt();
        expect($fresh->additional_fields['float_field'])->toBeFloat();
        expect($fresh->additional_fields['boolean_field'])->toBeBool();
        expect($fresh->additional_fields['null_field'])->toBeNull();
        expect($fresh->additional_fields['array_field'])->toBeArray();
        expect($fresh->additional_fields['object_field'])->toBeArray();
    });
});

describe('File Management', function (): void {
    it('can store assay file information', function (): void {
        $assay = Assay::factory()->create([
            'study_id' => $this->study->id,
            'assaydefinition_id' => $this->assayDefinition->id,
            'user_id' => $this->adminuser->id,
            'name' => 'Assay with File',
            'technologyPlatform' => 'PCR',
            'assayfile' => 'assay_data_2025.xlsx',
            'assayfilename' => 'PCR Results Spreadsheet',
        ]);

        expect($assay->assayfile)->toBe('assay_data_2025.xlsx');
        expect($assay->assayfilename)->toBe('PCR Results Spreadsheet');

        assertDatabaseHas('assays', [
            'id' => $assay->id,
            'assayfile' => 'assay_data_2025.xlsx',
            'assayfilename' => 'PCR Results Spreadsheet',
        ]);
    });

    it('can update file information', function (): void {
        $this->existingAssay->update([
            'assayfile' => 'updated_assay_file.csv',
            'assayfilename' => 'Updated Assay Results',
        ]);

        expect($this->existingAssay->assayfile)->toBe('updated_assay_file.csv');
        expect($this->existingAssay->assayfilename)->toBe('Updated Assay Results');
    });

    it('can clear file information', function (): void {
        // First set file information
        $this->existingAssay->update([
            'assayfile' => 'temp_file.dat',
            'assayfilename' => 'Temporary File',
        ]);

        // Then clear it
        $this->existingAssay->update([
            'assayfile' => null,
            'assayfilename' => null,
        ]);

        expect($this->existingAssay->assayfile)->toBeNull();
        expect($this->existingAssay->assayfilename)->toBeNull();
    });

    it('handles various file types', function (): void {
        $fileTypes = [
            ['file' => 'data.xlsx', 'name' => 'Excel Spreadsheet'],
            ['file' => 'results.csv', 'name' => 'CSV Data'],
            ['file' => 'protocol.pdf', 'name' => 'Protocol Document'],
            ['file' => 'raw_data.fcs', 'name' => 'Flow Cytometry Standard'],
            ['file' => 'analysis.R', 'name' => 'R Analysis Script'],
        ];

        foreach ($fileTypes as $index => $fileInfo) {
            $assay = Assay::factory()->create([
                'study_id' => $this->study->id,
                'assaydefinition_id' => $this->assayDefinition->id,
                'user_id' => $this->adminuser->id,
                'name' => "File Type Test {$index}",
                'technologyPlatform' => 'PCR',
                'assayfile' => $fileInfo['file'],
                'assayfilename' => $fileInfo['name'],
            ]);

            expect($assay->assayfile)->toBe($fileInfo['file']);
            expect($assay->assayfilename)->toBe($fileInfo['name']);
        }
    });
});

describe('AssayDefinition Integration', function (): void {
    it('maintains proper relationship with assay definition', function (): void {
        expect($this->existingAssay->assaydefinition)->toBeInstanceOf(AssayDefinition::class);
        expect($this->existingAssay->assaydefinition->id)->toBe($this->assayDefinition->id);
        expect($this->existingAssay->assaydefinition->name)->toBe('Test Assay Definition');
    });

    it('can create assays with different assay definitions', function (): void {
        $anotherDefinition = AssayDefinition::factory()->create([
            'name' => 'Another Assay Definition',
            'user_id' => $this->adminuser->id,
            'team_id' => $this->team->id,
            'active' => true,
            'technologyType' => 'ELISA',
        ]);

        $assay = Assay::factory()->create([
            'study_id' => $this->study->id,
            'assaydefinition_id' => $anotherDefinition->id,
            'user_id' => $this->adminuser->id,
            'name' => 'Different Definition Assay',
            'technologyPlatform' => 'ELISA',
        ]);

        expect($assay->assaydefinition->id)->toBe($anotherDefinition->id);
        expect($assay->assaydefinition->name)->toBe('Another Assay Definition');
        expect($assay->assaydefinition->technologyType)->toBe('ELISA');
    });

    it('respects assay definition additional fields structure', function (): void {
        $customDefinition = AssayDefinition::factory()->create([
            'name' => 'Custom Fields Definition',
            'user_id' => $this->adminuser->id,
            'team_id' => $this->team->id,
            'active' => true,
            'additional_fields' => [
                [
                    'field_name' => 'specimen_volume',
                    'field_type' => 'text',
                    'sub_type' => 'numeric',
                    'label' => 'Specimen Volume (μL)',
                    'required' => true,
                    'max_length' => 10,
                ],
                [
                    'field_name' => 'processing_date',
                    'field_type' => 'date',
                    'label' => 'Processing Date',
                    'required' => true,
                ],
                [
                    'field_name' => 'quality_level',
                    'field_type' => 'select',
                    'label' => 'Quality Level',
                    'field_options' => [
                        ['option_value' => 'high', 'option_label' => 'High'],
                        ['option_value' => 'medium', 'option_label' => 'Medium'],
                        ['option_value' => 'low', 'option_label' => 'Low'],
                    ],
                    'required' => false,
                ],
            ],
        ]);

        $assayFields = [
            'specimen_volume' => '50',
            'processing_date' => '2025-06-15',
            'quality_level' => 'high',
        ];

        $assay = Assay::factory()->create([
            'study_id' => $this->study->id,
            'assaydefinition_id' => $customDefinition->id,
            'user_id' => $this->adminuser->id,
            'name' => 'Custom Fields Assay',
            'technologyPlatform' => 'Custom',
            'additional_fields' => $assayFields,
        ]);

        expect($assay->additional_fields['specimen_volume'])->toBe('50');
        expect($assay->additional_fields['processing_date'])->toBe('2025-06-15');
        expect($assay->additional_fields['quality_level'])->toBe('high');
    });

    it('can handle assay definitions with no additional fields', function (): void {
        $simpleDefinition = AssayDefinition::factory()->create([
            'name' => 'Simple Definition',
            'user_id' => $this->adminuser->id,
            'team_id' => $this->team->id,
            'active' => true,
            'additional_fields' => null,
        ]);

        $assay = Assay::factory()->create([
            'study_id' => $this->study->id,
            'assaydefinition_id' => $simpleDefinition->id,
            'user_id' => $this->adminuser->id,
            'name' => 'Simple Assay',
            'technologyPlatform' => 'Basic',
            'additional_fields' => null, // Explicitly set to null
        ]);

        expect($assay->assaydefinition->additional_fields)->toBeNull();
        expect($assay->additional_fields)->toBeNull();
    });

    it('maintains consistency with assay definition state', function (): void {
        // Verify the definition is active
        expect($this->assayDefinition->active)->toBeTrue();

        // Create assay with active definition
        $assay = Assay::factory()->create([
            'study_id' => $this->study->id,
            'assaydefinition_id' => $this->assayDefinition->id,
            'user_id' => $this->adminuser->id,
            'name' => 'Active Definition Assay',
            'technologyPlatform' => 'PCR',
        ]);

        expect($assay->assaydefinition->active)->toBeTrue();

        // Deactivate the definition
        $this->assayDefinition->update(['active' => false]);

        // Verify existing assay still references the definition
        $assay->refresh();
        expect($assay->assaydefinition->active)->toBeFalse();
    });
});

describe('Business Logic Validation', function (): void {
    it('maintains study context isolation', function (): void {
        $anotherStudy = Study::factory()->create([
            'project_id' => $this->project->id,
            'identifier' => 'ANOTHER_STUDY_001',
            'title' => 'Another Test Study',
        ]);

        $anotherAssay = Assay::factory()->create([
            'study_id' => $anotherStudy->id,
            'assaydefinition_id' => $this->assayDefinition->id,
            'user_id' => $this->adminuser->id,
            'name' => 'Other Study Assay',
            'technologyPlatform' => 'ELISA',
        ]);

        // Verify assays are properly isolated by study
        expect($this->study->assays()->where('id', $anotherAssay->id)->exists())->toBeFalse();
        expect($anotherStudy->assays()->where('id', $this->existingAssay->id)->exists())->toBeFalse();

        expect($this->study->assays()->count())->toBe(1);
        expect($anotherStudy->assays()->count())->toBe(1);
    });

    it('preserves relationships on assay updates', function (): void {
        $originalStudyId = $this->existingAssay->study_id;
        $originalDefinitionId = $this->existingAssay->assaydefinition_id;

        $this->existingAssay->update([
            'name' => 'Updated Assay Name',
            'technologyPlatform' => 'Updated Platform',
        ]);

        expect($this->existingAssay->study_id)->toBe($originalStudyId);
        expect($this->existingAssay->assaydefinition_id)->toBe($originalDefinitionId);
        expect($this->existingAssay->study->id)->toBe($this->study->id);
        expect($this->existingAssay->assaydefinition->id)->toBe($this->assayDefinition->id);
    });

    it('maintains assay count integrity', function (): void {
        $initialCount = $this->study->assays()->count();

        // Create new assay
        Assay::factory()->create([
            'study_id' => $this->study->id,
            'assaydefinition_id' => $this->assayDefinition->id,
            'user_id' => $this->adminuser->id,
            'name' => 'Count Test Assay',
            'technologyPlatform' => 'PCR',
        ]);

        expect($this->study->assays()->count())->toBe($initialCount + 1);

        // Delete an assay
        $this->existingAssay->delete();

        expect($this->study->fresh()->assays()->count())->toBe($initialCount);
    });

    it('maintains data consistency across operations', function (): void {
        $assay = Assay::factory()->create([
            'study_id' => $this->study->id,
            'assaydefinition_id' => $this->assayDefinition->id,
            'user_id' => $this->adminuser->id,
            'name' => 'Consistency Test Assay',
            'technologyPlatform' => 'Original Platform',
            'additional_fields' => ['original' => 'data'],
        ]);

        // Perform multiple updates
        $assay->update(['name' => 'First Update']);
        $assay->update(['technologyPlatform' => 'Updated Platform']);
        $assay->update(['additional_fields' => ['updated' => 'data']]);

        $fresh = $assay->fresh();

        expect($fresh->name)->toBe('First Update');
        expect($fresh->technologyPlatform)->toBe('Updated Platform');
        expect($fresh->additional_fields)->toBe(['updated' => 'data']);
        expect($fresh->study_id)->toBe($this->study->id);
        expect($fresh->assaydefinition_id)->toBe($this->assayDefinition->id);
    });

    it('enforces user attribution for assays', function (): void {
        $assay = Assay::factory()->create([
            'study_id' => $this->study->id,
            'assaydefinition_id' => $this->assayDefinition->id,
            'user_id' => $this->adminuser->id,
            'name' => 'User Attribution Test',
            'technologyPlatform' => 'PCR',
        ]);

        expect($assay->user_id)->toBe($this->adminuser->id);
        expect($assay->created_by)->toBeInstanceOf(User::class);
        expect($assay->created_by->id)->toBe($this->adminuser->id);

        assertDatabaseHas('assays', [
            'id' => $assay->id,
            'user_id' => $this->adminuser->id,
        ]);
    });
});
