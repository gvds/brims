<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Artisan::call('shield:generate --all --panel=project --option=permissions --relationships --no-interaction');
        $this->call([
            UserSeeder::class,
            AssayDefinitionSeeder::class,
            ProjectSeeder::class,
            RoleSeeder::class,
            ProjectMembersSeeder::class,
            SpecimentypeSeeder::class,
            EventSeeder::class,
            SubjectSeeder::class,
            SpecimenSeeder::class,
            PublicationSeeder::class,
        ]);
    }
}
