<?php

namespace Database\Seeders;

use App\Models\AssayDefinition;
use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssayDefinitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Team::each(function ($team) {
            AssayDefinition::factory(8)
                ->create([
                    'team_id' => $team->id,
                    'user_id' => $team->leader_id,
                ]);
        });
    }
}
