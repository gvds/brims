<?php

namespace Database\Factories;

use App\Models\Institution;
use CountryEnums\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Institution>
 */
class InstitutionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->lexify('INST_????'),
            'country' => Country::random(),
        ];
    }
}
