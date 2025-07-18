<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'identifier' => fake()->unique()->word(),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'submission_date' => fake()->date(),
            'public_release_date' => null,
        ];
    }
}
