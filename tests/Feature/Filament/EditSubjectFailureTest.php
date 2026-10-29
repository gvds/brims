<?php

use App\Enums\SubjectStatus;
use App\Filament\Project\Resources\Subjects\Pages\EditSubject;
use App\Models\Project;
use App\Models\Site;
use App\Models\Subject;

it('does not show saved notification or persist changes when handleRecordUpdate fails', function (): void {
    // Prepare required related models (project, site, user)
    $project = Project::factory()->create([
        'team_id' => $this->team->id,
        'leader_id' => $this->adminuser->id,
    ]);

    $site = Site::factory()->create([
        'project_id' => $project->id,
        'name' => 'Test Site',
    ]);

    $subject = Subject::factory()->create([
        'subjectID' => 'TST12345',
        'project_id' => $project->id,
        'site_id' => $site->id,
        'user_id' => $this->adminuser->id,
        'status' => SubjectStatus::Generated,
    ]);

    // Use the pre-created admin user from the test harness
    $this->actingAs($this->adminuser);

    // Call handleRecordUpdate directly to avoid rendering the full Filament page.
    $page = new class extends EditSubject
    {
        public function callHandleRecordUpdate($record, array $data)
        {
            return $this->handleRecordUpdate($record, $data);
        }
    };

    $originalEnrolDate = $subject->fresh()->enrolDate;

    Subject::updating(function (): void {
        throw new RuntimeException('Update failed');
    });

    expect(fn () => $page->callHandleRecordUpdate($subject, ['enrolDate' => '2026-01-15']))
        ->toThrow(RuntimeException::class, 'Update failed');

    expect($subject->fresh()->status)->toBe(SubjectStatus::Generated)
        ->and($subject->fresh()->enrolDate)->toBe($originalEnrolDate);
});
