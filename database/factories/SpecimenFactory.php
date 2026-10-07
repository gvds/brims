<?php

namespace Database\Factories;

use App\Models\Specimen;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Specimen>
 */
class SpecimenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'barcode' => Str::upper(fake()->unique()->bothify('??########')),
        ];
    }
}
