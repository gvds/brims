<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Specimentype;
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
        $project_ids = Project::all()->pluck('id');
        $project_id = fake()->randomElement($project_ids);
        // $specimentype_ids = Specimentype::where('project_id', $project_id)->pluck('id');
        $storagespecimentypes = Specimentype::where('project_id', $project_id)->pluck('storageSpecimenType');
        $rackcapacity = 8;

        return [
            'name' => fake()->word(),
            'project_id' => $project_id,
            // 'specimentype_id' => fake()->randomElement($specimentype_ids),
            'storageSpecimenType' => fake()->randomElement($storagespecimentypes),
            'rack_extent' => 'Full',
            'startRack' => 1,
            'endRack' => 3,
            'startBox' => 'A',
            'endBox' => chr(ord('A') + $rackcapacity - 1),
            'rackCapacity' => $rackcapacity,
            'boxCapacity' => 25,
        ];
    }
}
