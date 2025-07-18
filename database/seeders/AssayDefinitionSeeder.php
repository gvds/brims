<?php

namespace Database\Seeders;

use App\Models\AssayDefinition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssayDefinitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AssayDefinition::factory(8)
            ->create([
                'user_id' => fake()->randomElement([1, 2]),
            ]);
    }
}
