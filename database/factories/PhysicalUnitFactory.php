<?php

namespace Database\Factories;

use App\Enums\StorageType;
use App\Models\UnitDefinition;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PhysicalUnit>
 */
class PhysicalUnitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user_ids = User::all()->pluck('id');
        $unitdefinition_ids = UnitDefinition::whereNot('storageType', StorageType::Biorepository)->pluck('id');

        return [
            'name' => fake()->unique()->word(),
            'unit_definition_id' => fake()->randomElement($unitdefinition_ids),
            'serial' => fake()->unique()->randomNumber(8, true),
            'user_id' => fake()->randomElement($user_ids),
            'available' => true,
        ];
    }
}
