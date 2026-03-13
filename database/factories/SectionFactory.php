<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Section>
 */
class SectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rows' => fake()->numberBetween(1, 5),
            'columns' => fake()->numberBetween(5, 12),
            'boxes' => fake()->numberBetween(5, 13),
            'positions' => 100,
        ];
    }
}
