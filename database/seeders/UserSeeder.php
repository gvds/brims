<?php

namespace Database\Seeders;

use App\Enums\SystemRoles;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = Team::all();

        Team::query()->each(function ($team) {
            $leader = User::factory(1)
                ->create([
                    'team_id' => $team->id,
                    'team_role' => 'Leader',
                    'system_role' => SystemRoles::User,
                ]);
            $team->update(['leader_id' => $leader->first()->id]);
            User::factory(4)
                ->create([
                    'team_id' => $team->id,
                    'team_role' => 'Member',
                    'system_role' => SystemRoles::User,
                ]);
        });
    }
}
