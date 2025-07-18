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
            'name' => Str::ucfirst($this->faker->words(3, true)),
            'description' => $this->faker->sentence(),
            'measurementType' => $this->faker->word(),
            'technologyType' => $this->faker->randomElement(['PCR', 'ELISA', 'scRNAseq', 'Luminex']),
            'additional_fields' => [
                [
                    'field_name' => $this->faker->word(),
                    'field_type' => 'radio',
                    'field_options' => [
                        ['option_value' => '1', 'option_label' => Str::ucfirst($this->faker->word())],
                        ['option_value' => '2', 'option_label' => Str::ucfirst($this->faker->word())],
                        ['option_value' => '3', 'option_label' => Str::ucfirst($this->faker->word())],
                    ],
                    'required' => $this->faker->boolean()
                ],
                [
                    'field_name' => $this->faker->word(),
                    'field_type' => 'text',
                    'max_length' => 255,
                    'sub_type' => 'integer',
                    'min_value' => $this->faker->numberBetween(1, 10),
                    'max_value' => $this->faker->numberBetween(11, 100),
                    'required' => $this->faker->boolean()
                ]
            ],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
