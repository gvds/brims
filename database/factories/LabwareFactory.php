<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Labware>
 */
class LabwareFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'barcodeFormat' => '^' . fake()->regexify('[A-Z]{' . fake()->numberBetween(2, 4) . '}' . '[0-9]{' . fake()->numberBetween(3, 8) . '}$'),
        ];
    }
}
