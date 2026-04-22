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
        Team::query()->with(['leader', 'members'])->get()->each(function (Team $team): void {
            $leader = $team->leader;

            if (! $leader instanceof User) {
                $leader = $team->members()->first();
            }

            if (! $leader instanceof User) {
                $leader = User::factory()->create([
                    'team_id' => $team->id,
                    'team_role' => 'Admin',
                ]);
            }

            if ($team->leader_id !== $leader->id) {
                $team->update(['leader_id' => $leader->id]);
            }

            $projects = Project::factory()
                ->count(3)
                ->for($team)
                ->hasStudies(4)
                ->hasSites(2)
                ->hasLabware(5)
                ->create([
                    'leader_id' => $leader->id,
                ]);
            $projects->each(function (Project $project) use ($team): void {
                $arms = Arm::factory()
                    ->count(3)
                    ->for($project)
                    ->sequence(fn(Sequence $sequence): array => [
                        'arm_num' => $sequence->index + 1,
                        'manual_enrol' => $sequence->index === 0 ? true : false,
                    ])
                    ->create();
                $arms->each(function (Arm $arm): void {
                    $arm->update([
                        'switcharms' => match ($arm->arm_num) {
                            1 => [$arm->id + 1, $arm->id + 2],
                            2 => [$arm->id + 1],
                            3 => null,
                        }
                    ]);
                });
            });
        });
    }
}
