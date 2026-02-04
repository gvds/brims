<?php

namespace Database\Seeders;

use App\Enums\SystemRoles;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!User::where('username', 'gvds')->exists()) {
            $user = User::factory()
                ->create([
                    'username' => 'gvds',
                    'firstname' => 'Gian',
                    'lastname' => 'van der Spuy',
                    'email' => 'gvds@sun.ac.za',
                    'telephone' => '27 (21) 938-9949',
                    'homesite' => 'SU_ZA',
                    'password' => Hash::make('password'),
                    'system_role' => SystemRoles::SuperAdmin,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            $team = Team::factory()
                ->create([
                    'leader_id' => $user->id,
                ]);
            $user->update([
                'team_id' => $team->id,
                'team_role' => 'Admin',
            ]);
        }

        $teams = Team::all();

        $users = User::factory(4)
            ->state(new Sequence(
                ['team_id' => $teams->first()->id],
                ['team_id' => $teams->last()->id],
            ))
            ->create([
                'team_role' => 'Member',
            ]);
    }
}
