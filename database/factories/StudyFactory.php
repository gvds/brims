<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Study>
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
            // `studies.identifier` is globally unique; `word()` has too small a space
            // and collides during large seed runs.
            'identifier' => fake()->unique()->bothify('study-????-####'),
            'title' => fake()->unique()->sentence(4),
            'description' => fake()->paragraph(),
            'submission_date' => fake()->date(),
            'studyfile' => fake()->word(),
        ];
    }
}
