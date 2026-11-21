<?php

namespace Database\Seeders;

use App\Models\Assay;
use App\Models\Study;
use App\Models\Team;
use Illuminate\Database\Seeder;

class StudySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Team::withoutGlobalScopes()->each(
            function (Team $team): void {
                $memberIds = $team->members()->pluck('id');
                $assayDefinitionIds = $team->assayDefinitions()->withoutGlobalScopes()->pluck('id');

                if ($memberIds->isEmpty() || $assayDefinitionIds->isEmpty()) {
                    return;
                }

                Study::factory()
                    ->count(3)
                    ->for($team)
                    ->create()
                    ->each(function (Study $study) use ($memberIds, $assayDefinitionIds): void {
                        Assay::factory()
                            ->count(2)
                            ->for($study)
                            ->state(fn (): array => [
                                'user_id' => $memberIds->random(),
                                'assaydefinition_id' => $assayDefinitionIds->random(),
                            ])
                            ->create();
                    });
            }
        );
    }
}
