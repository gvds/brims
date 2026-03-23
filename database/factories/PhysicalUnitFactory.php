<?php

namespace Database\Factories;

use App\Models\PhysicalUnit;
use App\Models\UnitDefinition;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PhysicalUnit>
 */
class PhysicalUnitFactory extends Factory
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
            'unit_definition_id' => UnitDefinition::factory(),
            'serial' => fake()->unique()->randomNumber(8, true),
            'user_id' => User::factory(),
            'available' => true,
        ];
    }
}
