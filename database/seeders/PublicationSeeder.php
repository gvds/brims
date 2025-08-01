<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Publication;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PublicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::all()->each(function ($project) {
            Publication::factory()
                ->count(random_int(1, 5))
                ->for($project)
                ->create();
        });
    }
}
