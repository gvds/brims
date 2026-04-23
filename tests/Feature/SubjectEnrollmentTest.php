<?php

use App\Enums\EventStatus;
use App\Enums\LabelStatus;
use App\Enums\SubjectStatus;
use App\Models\Arm;
use App\Models\Event;
use App\Models\Project;
use App\Models\Site;
use App\Models\Subject;
use App\Models\SubjectEvent;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Carbon;

it('marks the first event as logged during subject enrolment', function (): void {
    $team = Team::factory()->create();
    $leader = User::factory()->create();
    $project = Project::factory()
        ->for($team)
        ->for($leader, 'leader')
        ->create(['redcapProject_id' => null]);

    session(['currentProject' => $project]);

    $site = Site::factory()->for($project)->create();
    $user = User::factory()->create();
    $arm = Arm::factory()->create(['project_id' => $project->id, 'arm_num' => 1]);
    $event = Event::factory()->create([
        'arm_id' => $arm->id,
        'event_order' => 1,
        'offset' => 0,
        'autolog' => false,
    ]);

    $subject = Subject::factory()->create([
        'subjectID' => 'SUBJ01',
        'project_id' => $project->id,
        'site_id' => $site->id,
        'user_id' => $user->id,
        'arm_id' => $arm->id,
        'status' => SubjectStatus::Generated->value,
    ]);

    SubjectEvent::create([
        'subject_id' => $subject->id,
        'event_id' => $event->id,
        'iteration' => 1,
        'status' => EventStatus::Pending->value,
        'labelstatus' => LabelStatus::Pending->value,
    ]);

    $enrolDate = Carbon::today()->toDateString();

    $subject->enrol(['enrolDate' => $enrolDate]);

    $subject->refresh();
    $subjectEvent = SubjectEvent::where('subject_id', $subject->id)->first();

    expect($subject->status->value)->toBe(SubjectStatus::Enrolled->value);
    expect($subjectEvent->status->value)->toBe(EventStatus::Logged->value);
    expect($subjectEvent->labelstatus->value)->toBe(LabelStatus::Generated->value);
    expect($subjectEvent->eventDate)->toBe($enrolDate);
});
