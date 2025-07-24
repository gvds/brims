<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Pest\Collision\Events;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            AssayDefinitionSeeder::class,
            ProjectSeeder::class,
            SpecimentypeSeeder::class,
            EventSeeder::class,
        ]);
    }
}
