<?php

namespace Database\Factories;

use App\Models\StudyDesign;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StudyDesign>
 */
class StudydesignFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => fake()->unique()->sentence(3, false),
        ];
    }
}
