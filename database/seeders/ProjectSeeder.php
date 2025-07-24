<?php

namespace Database\Seeders;

use App\Models\Arm;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Team::all()->each(function (Team $team) {
            $projects = Project::factory()
                ->count(3)
                ->for($team)
                ->hasStudies(4)
                ->hasSites(4)
                ->create([
                    'leader_id' => $team->leader->id,
                ]);
            $projects->each(function (Project $project) use ($team) {
                $project->members()->attach($team->leader->id, ['role' => 'Admin']);
                User::whereNot('id', $team->leader->id)->get()->random(3)->each(fn(User $user) => $project->members()->attach($user->id, ['role' => 'Member']));
                Arm::factory()
                    ->count(3)
                    ->for($project)
                    ->sequence(fn(Sequence $sequence) => [
                        'arm_num' => $sequence->index + 1,
                        'manual_enrol' => $sequence->index === 0 ? true : false
                    ])
                    ->create();
            });
        });
    }
}
