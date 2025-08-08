<?php

namespace Database\Factories;

use App\Enums\PublicationStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Publication>
 */
class PublicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $names = [];
        $numberOfNames = fake()->numberBetween(1, 12);

        for ($i = 0; $i < $numberOfNames; $i++) {
            $names[] = fake()->unique()->name(); // Generate a unique fake name
        }

        $publication_status = fake()->randomElement(PublicationStatus::cases())->value;

        return [
            'pubmed_id' => $publication_status === 'published' ? fake()->unique()->numerify('#######') : null,
            'doi' => $publication_status === 'published' ? fake()->unique()->bothify('10.1234/??????.?????') : null,
            'publication_status' => $publication_status,
            'title' => fake()->sentence(nbWords: 20, variableNbWords: true),
            'authors' => $names,
            'publication_date' => $publication_status === 'published' ? fake()->year() : null,
        ];
    }
}
