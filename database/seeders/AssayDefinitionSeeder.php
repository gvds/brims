<?php

namespace Database\Seeders;

use App\Models\AssayDefinition;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class AssayDefinitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Team::query()->each(function (Team $team): void {
            $userId = $team->leader_id
                ?? $team->members()->orderBy('id')->value('id')
                ?? User::query()->orderBy('id')->value('id');

            if ($userId === null) {
                return;
            }

            AssayDefinition::factory(8)
                ->create([
                    'team_id' => $team->id,
                    'user_id' => $userId,
                ]);
        });
    }
}
