<?php

namespace Database\Factories;

use App\Models\Study;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Study>
 */
class StudyFactory extends Factory
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
            'studyfile' => fake()->word(),
        ];
    }
}
