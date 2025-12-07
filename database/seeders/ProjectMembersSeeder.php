<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectMembersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Team::all()->each(function (Team $team): void {
            Project::where('team_id', $team->id)
                ->get()
                ->each(
                    function (Project $project) use ($team): void {
                        $roles = Role::where('project_id', $project->id)->pluck('id', 'name');
                        $project->load('sites');
                        $project->members()->attach($team->leader->id, [
                            'role_id' => $roles['Admin'],
                            'site_id' => $project->sites->random(1)->first()->id,
                        ]);
                        User::whereNot('id', $team->leader->id)
                            ->get()
                            ->random(3)
                            ->each(
                                fn(User $user) => $project
                                    ->members()
                                    ->attach($user->id, [
                                        'role_id' => $roles['Member'],
                                        'site_id' => $project->sites->random(1)->first()->id,
                                    ])
                            );
                    }
                );
        });
    }
}
