<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\VirtualUnit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VirtualUnit>
 */
class VirtualUnitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'project_id' => Project::query()->inRandomOrder()->value('id') ?: Project::factory(),
            'storageSpecimenType' => fake()->word(),
            'rack_extent' => 'Full',
            'startRack' => 1,
            'endRack' => 3,
            'startBox' => 'A',
            'endBox' => 'H',
            'rackCapacity' => 8,
            'boxCapacity' => 25,
        ];
    }
}
