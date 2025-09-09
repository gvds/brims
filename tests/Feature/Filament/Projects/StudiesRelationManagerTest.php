<?php

/**
 * StudiesRelationManager Comprehensive Test Suite
 *
 * This test suite provides comprehensive coverage for the Projects StudiesRelationManager
 * functionality using Pest v4 testing framework and Filament v4 testing patterns.
 *
 * Coverage includes:
 * - Relation manager configuration and setup (5 tests)
 * - Study creation and validation (8 tests)
 * - Study data integrity (5 tests)
 * - Study lifecycle management (4 tests)
 * - File management (3 tests)
 * - Business logic validation (5 tests)
 *
 * Total: 30 tests focused on core business logic and data integrity
 *
 * Note: StudiesRelationManager manages the one-to-many relationship between
 * Projects and Studies with form fields including identifier, title, description,
 * submission dates, and file management for research study tracking.
 */

use App\Filament\Resources\Projects\RelationManagers\StudiesRelationManager;
use App\Models\Project;
use App\Models\Study;

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

    // Create some existing studies for testing
    $this->existingStudy = Study::factory()->create([
        'project_id' => $this->project->id,
        'identifier' => 'EXISTING_STUDY_001',
        'title' => 'Existing Study',
    ]);

    // Create the relation manager instance
    $this->relationManager = new StudiesRelationManager;
    $this->relationManager->ownerRecord = $this->project;
});

describe('Studies Relation Manager Configuration', function (): void {
    it('has correct relationship configuration', function (): void {
        $reflection = new ReflectionClass($this->relationManager);
        $relationshipProperty = $reflection->getProperty('relationship');
        $relationshipProperty->setAccessible(true);
        $relatedResourceProperty = $reflection->getProperty('relatedResource');
        $relatedResourceProperty->setAccessible(true);

        expect($relationshipProperty->getValue())->toBe('studies');
        expect($relatedResourceProperty->getValue())->toBe(App\Filament\Resources\Projects\Resources\Studies\StudyResource::class);
    });

    it('is not read-only', function (): void {
        expect($this->relationManager->isReadOnly())->toBeFalse();
    });

    it('can be instantiated correctly', function (): void {
        expect($this->relationManager)->toBeInstanceOf(StudiesRelationManager::class);
        expect($this->relationManager->ownerRecord)->toBeInstanceOf(Project::class);
    });

    it('has proper relationship with project', function (): void {
        $studies = $this->project->studies();

        expect($studies)->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\HasMany::class);
        expect($studies->getRelated())->toBeInstanceOf(Study::class);
    });

    it('maintains study records correctly', function (): void {
        $studyCount = $this->project->studies()->count();

        expect($studyCount)->toBeGreaterThan(0);
        expect($this->project->studies()->first())->toBeInstanceOf(Study::class);
    });
});

describe('Study Creation and Validation', function (): void {
    it('can create a basic study', function (): void {
        $study = Study::factory()->create([
            'project_id' => $this->project->id,
            'identifier' => 'TEST_STUDY_001',
            'title' => 'Test Study Creation',
        ]);

        expect($study->project_id)->toBe($this->project->id);
        expect($study->identifier)->toBe('TEST_STUDY_001');
        expect($study->title)->toBe('Test Study Creation');

        assertDatabaseHas('studies', [
            'id' => $study->id,
            'project_id' => $this->project->id,
            'identifier' => 'TEST_STUDY_001',
        ]);
    });

    it('can create study with description', function (): void {
        $study = Study::factory()->create([
            'project_id' => $this->project->id,
            'identifier' => 'DESC_STUDY_001',
            'title' => 'Study with Description',
            'description' => 'This is a detailed study description explaining the research objectives.',
        ]);

        expect($study->description)->toBe('This is a detailed study description explaining the research objectives.');
        assertDatabaseHas('studies', [
            'id' => $study->id,
            'description' => 'This is a detailed study description explaining the research objectives.',
        ]);
    });

    it('can create study with submission date', function (): void {
        $submissionDate = '2025-06-15';

        $study = Study::factory()->create([
            'project_id' => $this->project->id,
            'identifier' => 'SUBMIT_STUDY_001',
            'title' => 'Study with Submission Date',
            'submission_date' => $submissionDate,
        ]);

        expect($study->submission_date)->toBe($submissionDate);
        assertDatabaseHas('studies', [
            'id' => $study->id,
            'submission_date' => $submissionDate,
        ]);
    });

    it('can create study with public release date', function (): void {
        $releaseDate = '2025-12-01';

        $study = Study::factory()->create([
            'project_id' => $this->project->id,
            'identifier' => 'RELEASE_STUDY_001',
            'title' => 'Study with Release Date',
            'public_release_date' => $releaseDate,
        ]);

        expect($study->public_release_date)->toBe($releaseDate);
        assertDatabaseHas('studies', [
            'id' => $study->id,
            'public_release_date' => $releaseDate,
        ]);
    });

    it('maintains referential integrity when creating studies', function (): void {
        $study = Study::factory()->create([
            'project_id' => $this->project->id,
            'identifier' => 'INTEGRITY_STUDY_001',
            'title' => 'Referential Integrity Test',
        ]);

        // Verify the study belongs to the correct project
        expect($study->project->id)->toBe($this->project->id);

        // Refresh the project to get updated studies count
        $this->project->refresh();
        expect($this->project->studies()->where('id', $study->id)->exists())->toBeTrue();
    });

    it('can update existing study', function (): void {
        $originalTitle = $this->existingStudy->title;
        $newTitle = 'Updated Study Title';

        $this->existingStudy->update(['title' => $newTitle]);
        $this->existingStudy->refresh();

        expect($this->existingStudy->title)->toBe($newTitle);
        expect($this->existingStudy->title)->not()->toBe($originalTitle);

        assertDatabaseHas('studies', [
            'id' => $this->existingStudy->id,
            'title' => $newTitle,
        ]);
    });

    it('can delete study', function (): void {
        $studyId = $this->existingStudy->id;

        $this->existingStudy->delete();

        assertDatabaseMissing('studies', [
            'id' => $studyId,
        ]);
    });

    it('enforces unique identifier constraint', function (): void {
        $identifier = 'UNIQUE_STUDY_001';

        // Create first study with identifier
        Study::factory()->create([
            'project_id' => $this->project->id,
            'identifier' => $identifier,
            'title' => 'First Study',
        ]);

        // Attempting to create second study with same identifier should fail
        expect(function () use ($identifier) {
            Study::factory()->create([
                'project_id' => $this->project->id,
                'identifier' => $identifier,
                'title' => 'Second Study',
            ]);
        })->toThrow(Exception::class);
    });
});

describe('Study Data Integrity', function (): void {
    it('preserves date formats correctly', function (): void {
        $submissionDate = '2025-03-15';
        $releaseDate = '2025-09-30';

        $study = Study::factory()->create([
            'project_id' => $this->project->id,
            'identifier' => 'DATE_STUDY_001',
            'title' => 'Date Format Test',
            'submission_date' => $submissionDate,
            'public_release_date' => $releaseDate,
        ]);

        $fresh = $study->fresh();

        expect($fresh->submission_date)->toBe($submissionDate);
        expect($fresh->public_release_date)->toBe($releaseDate);
    });

    it('handles null values correctly', function (): void {
        $study = Study::factory()->create([
            'project_id' => $this->project->id,
            'identifier' => 'NULL_STUDY_001',
            'title' => 'Null Values Test',
            'description' => null,
            'submission_date' => null,
            'public_release_date' => null,
            'studyfile' => null,
            'studyfilename' => null,
        ]);

        expect($study->description)->toBeNull();
        expect($study->submission_date)->toBeNull();
        expect($study->public_release_date)->toBeNull();
        expect($study->studyfile)->toBeNull();
        expect($study->studyfilename)->toBeNull();
    });

    it('maintains text field integrity', function (): void {
        $longDescription = str_repeat('This is a very long description that tests text field capacity. ', 50);

        $study = Study::factory()->create([
            'project_id' => $this->project->id,
            'identifier' => 'TEXT_STUDY_001',
            'title' => 'Text Integrity Test',
            'description' => $longDescription,
        ]);

        expect($study->description)->toBe($longDescription);
        expect(strlen($study->description))->toBeGreaterThan(1000);
    });

    it('preserves identifier case sensitivity', function (): void {
        $study = Study::factory()->create([
            'project_id' => $this->project->id,
            'identifier' => 'CaseSensitive_Study_001',
            'title' => 'Case Sensitivity Test',
        ]);

        expect($study->identifier)->toBe('CaseSensitive_Study_001');
        assertDatabaseHas('studies', [
            'identifier' => 'CaseSensitive_Study_001',
        ]);
    });

    it('handles special characters in fields', function (): void {
        $study = Study::factory()->create([
            'project_id' => $this->project->id,
            'identifier' => 'SPECIAL_CHARS_001',
            'title' => 'Study with Special Characters: αβγ & émojis 🧬',
            'description' => 'This study includes special characters: ñáéíóú, symbols @#$%, and numbers 123.',
        ]);

        expect($study->title)->toContain('αβγ & émojis 🧬');
        expect($study->description)->toContain('ñáéíóú');
        expect($study->description)->toContain('@#$%');
    });
});

describe('Study Lifecycle Management', function (): void {
    it('tracks creation and update timestamps', function (): void {
        $beforeCreation = now();

        $study = Study::factory()->create([
            'project_id' => $this->project->id,
            'identifier' => 'TIMESTAMP_STUDY_001',
            'title' => 'Timestamp Test',
        ]);

        $afterCreation = now();

        expect($study->created_at)->toBeGreaterThanOrEqual($beforeCreation->subSecond());
        expect($study->created_at)->toBeLessThanOrEqual($afterCreation->addSecond());
        expect($study->updated_at)->toBeGreaterThanOrEqual($beforeCreation->subSecond());
        expect($study->updated_at)->toBeLessThanOrEqual($afterCreation->addSecond());
    });

    it('updates timestamp on modification', function (): void {
        $originalUpdatedAt = $this->existingStudy->updated_at;

        // Wait a moment to ensure timestamp difference
        sleep(1);

        $this->existingStudy->update(['title' => 'Modified Title']);

        expect($this->existingStudy->updated_at)->toBeGreaterThan($originalUpdatedAt);
    });

    it('can create study with both dates', function (): void {
        $submissionDate = '2025-01-15';
        $releaseDate = '2025-12-15';

        $study = Study::factory()->create([
            'project_id' => $this->project->id,
            'identifier' => 'DUAL_DATE_STUDY_001',
            'title' => 'Study with Both Dates',
            'submission_date' => $submissionDate,
            'public_release_date' => $releaseDate,
        ]);

        expect($study->submission_date)->toBe($submissionDate);
        expect($study->public_release_date)->toBe($releaseDate);
        expect($study->public_release_date)->toBeGreaterThan($study->submission_date);
    });

    it('handles cascade deletion from project', function (): void {
        $newProject = Project::factory()->create([
            'team_id' => $this->team->id,
            'leader_id' => $this->adminuser->id,
        ]);

        $study = Study::factory()->create([
            'project_id' => $newProject->id,
            'identifier' => 'CASCADE_STUDY_001',
            'title' => 'Cascade Test Study',
        ]);

        $studyId = $study->id;

        // Delete the project - should cascade to study
        $newProject->delete();

        assertDatabaseMissing('studies', [
            'id' => $studyId,
        ]);
    });
});

describe('File Management', function (): void {
    it('can store study file information', function (): void {
        $study = Study::factory()->create([
            'project_id' => $this->project->id,
            'identifier' => 'FILE_STUDY_001',
            'title' => 'Study with File',
            'studyfile' => 'study_data_2025.xlsx',
            'studyfilename' => 'Research Data Spreadsheet',
        ]);

        expect($study->studyfile)->toBe('study_data_2025.xlsx');
        expect($study->studyfilename)->toBe('Research Data Spreadsheet');

        assertDatabaseHas('studies', [
            'id' => $study->id,
            'studyfile' => 'study_data_2025.xlsx',
            'studyfilename' => 'Research Data Spreadsheet',
        ]);
    });

    it('can update file information', function (): void {
        $this->existingStudy->update([
            'studyfile' => 'updated_file.pdf',
            'studyfilename' => 'Updated Study Document',
        ]);

        expect($this->existingStudy->studyfile)->toBe('updated_file.pdf');
        expect($this->existingStudy->studyfilename)->toBe('Updated Study Document');
    });

    it('can clear file information', function (): void {
        // First set file information
        $this->existingStudy->update([
            'studyfile' => 'temp_file.doc',
            'studyfilename' => 'Temporary Document',
        ]);

        // Then clear it
        $this->existingStudy->update([
            'studyfile' => null,
            'studyfilename' => null,
        ]);

        expect($this->existingStudy->studyfile)->toBeNull();
        expect($this->existingStudy->studyfilename)->toBeNull();
    });
});

describe('Business Logic Validation', function (): void {
    it('maintains project context isolation', function (): void {
        $otherProject = Project::factory()->create([
            'team_id' => $this->team->id,
            'leader_id' => $this->adminuser->id,
        ]);

        // Temporarily switch session to other project
        session()->put('currentProject', $otherProject);

        $otherStudy = Study::factory()->create([
            'project_id' => $otherProject->id,
            'identifier' => 'OTHER_PROJECT_STUDY_001',
            'title' => 'Other Project Study',
        ]);

        // Switch back to original project
        session()->put('currentProject', $this->project);

        // Verify studies are properly isolated by project
        expect($this->project->studies()->where('id', $otherStudy->id)->exists())->toBeFalse();

        // Switch to other project to test isolation
        session()->put('currentProject', $otherProject);
        expect($otherProject->studies()->where('id', $this->existingStudy->id)->exists())->toBeFalse();

        // Reset to original project
        session()->put('currentProject', $this->project);
        expect($this->project->studies()->count())->toBe(1);

        session()->put('currentProject', $otherProject);
        expect($otherProject->studies()->count())->toBe(1);

        // Reset session
        session()->put('currentProject', $this->project);
    });

    it('preserves relationships on study updates', function (): void {
        $originalProjectId = $this->existingStudy->project_id;

        $this->existingStudy->update([
            'title' => 'Updated Study Title',
            'description' => 'Updated description',
        ]);

        expect($this->existingStudy->project_id)->toBe($originalProjectId);
        expect($this->existingStudy->project->id)->toBe($this->project->id);
    });

    it('enforces required field validation', function (): void {
        // Identifier is required
        expect(function () {
            Study::factory()->create([
                'project_id' => $this->project->id,
                'identifier' => null,
                'title' => 'Test Study',
            ]);
        })->toThrow(Exception::class);

        // Title is required
        expect(function () {
            Study::factory()->create([
                'project_id' => $this->project->id,
                'identifier' => 'VALID_ID_001',
                'title' => null,
            ]);
        })->toThrow(Exception::class);
    });

    it('maintains study count integrity', function (): void {
        $initialCount = $this->project->studies()->count();

        // Create new study
        Study::factory()->create([
            'project_id' => $this->project->id,
            'identifier' => 'COUNT_STUDY_001',
            'title' => 'Count Test Study',
        ]);

        expect($this->project->studies()->count())->toBe($initialCount + 1);

        // Delete a study
        $this->existingStudy->delete();

        expect($this->project->fresh()->studies()->count())->toBe($initialCount);
    });

    it('maintains data consistency across operations', function (): void {
        $study = Study::factory()->create([
            'project_id' => $this->project->id,
            'identifier' => 'CONSISTENCY_STUDY_001',
            'title' => 'Original Title',
            'description' => 'Original Description',
            'submission_date' => '2025-01-01',
        ]);

        // Perform multiple updates
        $study->update(['title' => 'First Update']);
        $study->update(['description' => 'Updated Description']);
        $study->update(['submission_date' => '2025-02-01']);

        $fresh = $study->fresh();

        expect($fresh->title)->toBe('First Update');
        expect($fresh->description)->toBe('Updated Description');
        expect($fresh->submission_date)->toBe('2025-02-01');
        expect($fresh->project_id)->toBe($this->project->id);
    });
});
