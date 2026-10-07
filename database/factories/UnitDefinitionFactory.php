<?php

namespace Database\Factories;

use App\Models\UnitDefinition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UnitDefinition>
 */
class UnitDefinitionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'orientation' => fake()->randomElement(['Chest', 'Upright']),
            'sectionLayout' => fake()->randomElement(['Horizontal', 'Vertical']),
            'boxDesignation' => fake()->randomElement(['Alpha', 'Numeric']),
            'storageType' => fake()->randomElement(['Minus 80', 'Liquid Nitrogen']),
            'rackOrder' => fake()->randomElement(['Row-wise', 'Column-wise']),
        ];
    }
}
