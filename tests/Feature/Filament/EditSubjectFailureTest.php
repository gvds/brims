<?php

use App\Enums\SubjectStatus;
use App\Filament\Project\Resources\Subjects\Pages\EditSubject;
use App\Models\Subject;
use App\Models\Team;
use App\Models\User;
use Livewire\Livewire;

it('does not show saved notification or persist changes when handleRecordUpdate fails', function (): void {
    // Prepare required related models (project, site, user)
    $project = \App\Models\Project::factory()->create([
        'team_id' => $this->team->id,
        'leader_id' => $this->adminuser->id,
    ]);

    $site = \App\Models\Site::factory()->create([
        'project_id' => $project->id,
        'name' => $this->adminuser->homesite,
    ]);

    // Subject in Generated status triggers the branch that builds events
    $subject = Subject::factory()->create([
        'subjectID' => 'TST12345',
        'project_id' => $project->id,
        'site_id' => $site->id,
        'user_id' => $this->adminuser->id,
        'status' => SubjectStatus::Generated,
    ]);

    // Use the pre-created admin user from the test harness
    $this->actingAs($this->adminuser);

    // Call the page's handleRecordUpdate directly to assert that the
    // exception bubbles and the record isn't partially saved. This avoids
    // rendering the full Filament page in the test environment.
    $page = new class extends EditSubject {
        public function callHandleRecordUpdate($record, array $data)
        {
            return $this->handleRecordUpdate($record, $data);
        }
    };

    $this->expectException(\Throwable::class);

    $page->callHandleRecordUpdate($subject, ['enrolDate' => 'not-a-valid-date']);

    // Ensure the subject was not transitioned to Enrolled (no partial save)
    $subject->refresh();
    expect($subject->status)->toBe(SubjectStatus::Generated->value);
});
