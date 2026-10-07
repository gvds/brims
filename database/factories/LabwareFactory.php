<?php

namespace Database\Factories;

use App\Models\Labware;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Labware>
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
            'barcodeFormat' => '^'.fake()->regexify('[A-Z]{'.fake()->numberBetween(2, 4).'}').'\d{'.fake()->numberBetween(3, 8).'}$',
        ];
    }
}
