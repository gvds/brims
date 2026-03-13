<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Specimentype;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VirtualUnit>
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
        $project_ids = Project::all()->pluck('id');
        $project_id = fake()->randomElement($project_ids);
        $specimentype_ids = Specimentype::where('project_id', $project_id)->pluck('id');

        return [
            'name' => fake()->word(),
            'project_id' => $project_id,
            'specimentype_id' => fake()->randomElement($specimentype_ids),
            'rack_extent' => 'Full',
            'startRack' => 1,
            'endRack' => 3,
            'startBox' => 'A',
            'endBox' => chr(ord('A') + 12),
            'rackCapacity' => 8,
            'boxCapacity' => 25,
        ];
    }
}
