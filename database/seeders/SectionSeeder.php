<?php

namespace Database\Seeders;

use App\Models\Section;
use App\Models\UnitDefinition;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UnitDefinition::each(function ($unitdefinition) {
            $count = random_int(1, 3);
            for ($section_number = 1; $section_number <= $count; $section_number++) {
                Section::factory(
                    1,
                    [
                        'unit_definition_id' => $unitdefinition->id,
                        'section_number' => $section_number,
                    ]
                )
                    ->create();
            }
        });
    }
}
