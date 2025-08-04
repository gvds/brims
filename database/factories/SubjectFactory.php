<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subject>
 */
class SubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startdate = fake()->date();
        return [
            // 'subjectID' =>,
            // 'site_id' =>,
            // 'user_id' =>,
            'firstname' => fake()->firstName(),
            'lastname' => fake()->lastName(),
            'address' => explode("\n", fake()->address()),
            'enrolDate' => $startdate,
            // 'arm_id' =>,
            'armBaselineDate' => $startdate,
            'status' => 0,
        ];
    }
}
