<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Specimentype;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpecimentypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::withoutGlobalScopes()->get()->each(
            function (Project $project): void {
                $primarySpecimentypes = Specimentype::factory()
                    ->count(3)
                    ->for($project)
                    ->create([
                        'primary' => true,
                    ]);
                $primarySpecimentypes->each(
                    function (Specimentype $parentSpecimentype) use ($project): void {
                        Specimentype::factory()
                            ->count(2)
                            ->for($project)
                            ->create([
                                'parentSpecimenType_id' => $parentSpecimentype->id,
                                'primary' => false,
                            ]);
                    }
                );
            }
        );
    }
}
