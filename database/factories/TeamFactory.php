<?php

namespace Database\Factories;

use App\Models\Institution;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Team>
 */
class TeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'institution_id' => Institution::query()->inRandomOrder()->value('id') ?: Institution::factory(),
            'name' => fake()->unique()->word(),
            'description' => fake()->sentence(),
        ];
    }
}
