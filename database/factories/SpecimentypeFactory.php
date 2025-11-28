<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Specimentype>
 */
class SpecimentypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $storageConfig = $this->getRandomStorageConfiguration();
        $fields = [
            'name' => fake()->unique()->word(),
            'primary' => fake()->boolean(),
            'aliquots' => fake()->numberBetween(1, 3),
            'pooled' => fake()->boolean(),
            'defaultVolume' => fake()->numberBetween(5, 100),
            'volumeUnit' => fake()->randomElement(['µl', 'ml']),
            'transferDestinations' => [
                ['destination' => fake()->word()],
                ['destination' => fake()->word()],
            ],
            'specimenGroup' => fake()->randomElement(['Group A', 'Group B', 'Group C']),
            // 'labware_id' => fake()->randomElement(
            //     \App\Models\Labware::pluck('id')->toArray()
            // ),
            // 'store' => fake()->boolean(),
            // 'storageDestination' => fake()->randomElement(['Internal', 'Biorepository', null]),
            // 'storageSpecimenType' => fake()->word(),
            'active' => true,
        ];

        return array_merge($fields, $storageConfig);
    }

    private function getRandomStorageConfiguration(): array
    {
        $store = fake()->boolean();
        if ($store) {
            return [
                'store' => true,
                'storageDestination' => fake()->randomElement(['Internal', 'Biorepository']),
                'storageSpecimenType' => fake()->word(),
            ];
        }
        return [];
    }

    /**
     * @param mixed $project_id
     * @return Factory
     */
    public function projectLabware($project_id): Factory
    {
        return $this->state(function (array $attributes) use ($project_id) {
            return [
                'labware_id' => fake()->randomElement(
                    \App\Models\Labware::where('project_id', $project_id)->pluck('id')
                )
            ];
        });
    }
}
