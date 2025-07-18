<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            $team = Team::factory(1)
                ->create([
                    'leader_id' => $user->id,
                ]);
            $user->update([
                'team_id' => $team[0]->id,
                'team_role' => 'Admin',
            ]);
            DB::table('model_has_roles')->insert([
                'role_id' => 1,
                'model_type' => User::class,
                'model_id' => $user->id,
                // 'team_id' => $user->team_id,
            ]);

            $user2 = User::factory()
                ->create([
                    'username' => 'asparks',
                    'firstname' => 'Anel',
                    'lastname' => 'Sparks',
                    'email' => 'asparks@sun.ac.za',
                    'telephone' => '27 (84) 250-9890',
                    'homesite' => 'SU_ZA',
                    'password' => Hash::make('password'),
                ]);
            $team2 = Team::factory(1)
                ->create([
                    'leader_id' => $user2->id,
                ]);
            // $user2->assignRole('super_admin', $team2[0]->id);
            $user2->update([
                'team_id' => $team2[0]->id,
                'team_role' => 'Admin',
            ]);
            DB::table('model_has_roles')->insert([
                'role_id' => 1,
                'model_type' => User::class,
                'model_id' => $user2->id,
                // 'team_id' => $user2->team_id,
            ]);
        }

        User::factory(3)
            ->create([
                'team_id' => $team[0]->id,
                'team_role' => 'Member',
            ]);
        User::factory(3)
            ->create([
                'team_id' => $team2[0]->id,
                'team_role' => 'Member',
            ]);

        // User::factory(5)->create();
    }
}
