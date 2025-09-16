<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\CarbonImmutable;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::with(['members', 'arms'])->withoutGlobalScopes()->get()->each(function ($project): void {
            $subjects = Subject::factory()
                ->count(6)
                ->for($project)
                ->sequence(function (Sequence $sequence) use ($project) {
                    $member = $project->members->random();
                    return [
                        'user_id' => $member->id,
                        'site_id' => $member->pivot->site_id,
                        'arm_id' => $project->arms->first()->id,
                        'subjectID' => $project->subjectID_prefix . Str::padLeft($sequence->index + 1, $project->subjectID_digits, '0'),
                        'status' => 1,
                    ];
                })
                ->create([
                    'arm_id' => $project->arms->first()->id ?? null,
                ]);

            $first_arm = $project->arms->first();

            $subjects->each(function ($subject) use ($project, $first_arm): void {
                $enrolDate = new CarbonImmutable($subject->enrolDate);
                $events = $project->events->where('arm_id', $first_arm->id);
                $events->each(fn($event) => $subject->events()->attach($event, [
                    'eventDate' => $enrolDate->addDays($event->offset),
                    'minDate' => isset($event->offset_ante_window) ? $enrolDate->addDays($event->offset - $event->offset_ante_window) : null,
                    'maxDate' => isset($event->offset_post_window) ? $enrolDate->addDays($event->offset + $event->offset_post_window) : null,
                    'iteration' => 1,
                    'status' => $event->autolog ? 3 : (isset($event->offset_ante_window) ? fake()->randomElement([0, 3]) : 0),
                ]));
            });

            $subjects = Subject::factory()
                ->count(3)
                ->for($project)
                ->sequence(function (Sequence $sequence) use ($project) {
                    $member = $project->members->random();
                    return [
                        'user_id' => $member->id,
                        'site_id' => $member->pivot->site_id,
                        'arm_id' => $project->arms->first()->id,
                        'subjectID' => $project->subjectID_prefix . Str::padLeft($sequence->index + 1 + 6, $project->subjectID_digits, '0'),
                        'firstname' => null,
                        'lastname' => null,
                        'address' => null,
                        'enrolDate' => null,
                        'armBaselineDate' => null,
                        'status' => 0,
                    ];
                })
                ->create([
                    'arm_id' => $project->arms->first()->id ?? null,
                ]);
        });
    }
}
