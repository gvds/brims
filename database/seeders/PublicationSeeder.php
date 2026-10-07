<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Publication;
use App\Models\Scopes\ProjectScope;
use Illuminate\Database\Seeder;

class PublicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::withoutGlobalScope(ProjectScope::class)->get()->each(function ($project): void {
            Publication::factory()
                ->count(random_int(1, 5))
                ->for($project)
                ->create();
        });
    }
}
