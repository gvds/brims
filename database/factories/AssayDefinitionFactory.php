<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AssayDefinition>
 */
class AssayDefinitionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => Str::ucfirst(fake()->words(3, true)),
            'description' => fake()->sentence(),
            'measurementType' => fake()->word(),
            'technologyType' => fake()->randomElement(['PCR', 'ELISA', 'scRNAseq', 'Luminex']),
            'additional_fields' => [
                [
                    'field_name' => fake()->word(),
                    'field_type' => 'radio',
                    'field_options' => [
                        ['option_value' => '1', 'option_label' => Str::ucfirst(fake()->word())],
                        ['option_value' => '2', 'option_label' => Str::ucfirst(fake()->word())],
                        ['option_value' => '3', 'option_label' => Str::ucfirst(fake()->word())],
                    ],
                    'required' => fake()->boolean()
                ],
                [
                    'field_name' => fake()->word(),
                    'field_type' => 'text',
                    'max_length' => 255,
                    'sub_type' => 'integer',
                    'min_value' => fake()->numberBetween(1, 10),
                    'max_value' => fake()->numberBetween(11, 100),
                    'required' => fake()->boolean()
                ]
            ],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
