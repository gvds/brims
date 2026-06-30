<?php

namespace Database\Seeders;

use App\Enums\SystemRoles;
use App\Models\Institution;
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
