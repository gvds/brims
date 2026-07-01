<?php

namespace Database\Seeders;

use App\Models\UnitDefinition;
use Illuminate\Database\Seeder;

class UnitDefinitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UnitDefinition::factory(3)
            ->create();
        $this->call([
            SectionSeeder::class,
        ]);
    }
}
