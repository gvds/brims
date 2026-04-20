<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\StudyDesign;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
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
            'title' => fake()->unique()->sentence(4),
            'description' => fake()->paragraph(),
            'submission_date' => fake()->date(),
            'public_release_date' => null,
            'subjectID_prefix' => fake()->regexify('[A-Z]{2,5}'),
            'subjectID_digits' => fake()->numberBetween(3, 5),
            'storageDesignation' => fake()->word(),
            'team_id' => Team::factory(),
            'leader_id' => User::factory(),
            'study_design_id' => StudyDesign::factory(),
        ];
    }
}
