<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Assay>
 */
class AssayFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'technologyPlatform' => fake()->randomElement(['PCR', 'ELISA', 'scRNAseq', 'Luminex', 'Mass Spectrometry']),
            'uri' => fake()->optional()->url(),
            'location' => fake()->optional()->words(2, true),
            'additional_fields' => fake()->optional()->randomElement([
                null,
                ['field1' => fake()->word(), 'field2' => fake()->numberBetween(1, 100)],
                ['experiment_type' => fake()->word(), 'samples' => fake()->numberBetween(10, 50)],
            ]),
            'assayfile' => fake()->optional()->word() . '.xlsx',
            'assayfilename' => fake()->optional()->sentence(3),
        ];
    }
}
