<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Event>
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
            'name' => fake()->unique()->words(2, true),
            'offset_ante_window' => fake()->numberBetween(0, 3),
            'offset_post_window' => fake()->numberBetween(0, 5),
            'name_labels' => fake()->numberBetween(1, 3),
            'subject_event_labels' => fake()->numberBetween(1, 6),
            'subject_id_labels' => fake()->numberBetween(1, 3),
        ];
    }
}
