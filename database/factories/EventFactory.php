<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
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
            'offset_ante_window' => fake()->numberBetween(0, 3),
            'offset_post_window' => fake()->numberBetween(0, 5),
            'name_labels' => fake()->numberBetween(1, 3),
            'subject_event_labels' => fake()->numberBetween(1, 6),
            'study_id_labels' => fake()->numberBetween(1, 3),
        ];
    }
}
