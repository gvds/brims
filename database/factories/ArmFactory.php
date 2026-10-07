<?php

namespace Database\Factories;

use App\Models\Arm;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Arm>
 */
class ArmFactory extends Factory
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
            'manual_enrol' => fake()->boolean(),
        ];
    }
}
