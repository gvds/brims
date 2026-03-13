<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UnitDefinitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\UnitDefinition::factory(3)
            ->create();
        $this->call([
            SectionSeeder::class,
        ]);
    }
}
