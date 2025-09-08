<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Arm>
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
            'name' => fake()->word(),
            // 'arm_num' => fake()->unique()->numberBetween(1, 10 ** 6),
            'manual_enrol' => fake()->boolean(),
        ];
    }
}
