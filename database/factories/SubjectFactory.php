<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subject>
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
        $startdate = fake()->dateTimeBetween('-1 year', 'now');

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
