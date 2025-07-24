<?php

namespace Database\Seeders;

use App\Models\Arm;
use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Arm::all()->each(
            function (Arm $arm) {
                Event::factory()
                    ->count(4)
                    ->for($arm)
                    ->sequence(function (Sequence $sequence) {
                        $offset = $sequence->index === 0 ? 0 : ($offset ?? 0) + fake()->numberBetween(7, 24);
                        return [
                            'event_order' => $sequence->index + 1,
                            'offset' => $offset,
                            'autolog' => $sequence->index === 0 ? true : false,
                        ];
                    })
                    ->create();
            }
        );
    }
}
