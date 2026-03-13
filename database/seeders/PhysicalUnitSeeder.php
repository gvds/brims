<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\PhysicalUnit;
use App\Models\VirtualUnit;
use Illuminate\Database\Seeder;

class PhysicalUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PhysicalUnit::factory(5)
            ->create()
            ->each(function ($physicalUnit) {
                // $virtualunit_count = random_int(1, 3);
                $physicalUnit->virtualUnits()->saveMany(
                    VirtualUnit::factory(1)
                        ->create([
                            'physical_unit_id' => $physicalUnit->id,
                        ])
                        ->each(function ($virtualUnit) {
                            for ($rack = $virtualUnit->startRack; $rack <= $virtualUnit->endRack; $rack++) {
                                for ($box = $virtualUnit->startBox; $box <= $virtualUnit->endBox; $box++) {
                                    for ($position = 1; $position <= $virtualUnit->boxCapacity; $position++) {
                                        Location::create([
                                            'virtual_unit_id' => $virtualUnit->id,
                                            'rack' => $rack,
                                            'box' => $box,
                                            'position' => $position,
                                        ]);
                                    }
                                }
                            }
                        })
                );
            });
    }
}
