<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::with(['members', 'arms'])->get()->each(function ($project) {
            Subject::factory()
                ->count(10)
                ->for($project)
                ->sequence(function (Sequence $sequence) use ($project) {
                    $member = $project->members->random();
                    return [
                        'user_id' => $member->id,
                        'site_id' => $member->pivot->site_id,
                        'arm_id' => $project->arms->first()->id,
                        'subjectID' => $project->subjectID_prefix . Str::padLeft($sequence->index + 1, $project->subjectID_digits, '0'),
                    ];
                })
                ->create([
                    'arm_id' => $project->arms->first()->id ?? null,
                ]);
        });
    }
}
