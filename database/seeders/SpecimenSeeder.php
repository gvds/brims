<?php

namespace Database\Seeders;

use App\Enums\SpecimenStatus;
use App\Models\Specimen;
use App\Models\Specimentype;
use App\Models\SubjectEvent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class SpecimenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SubjectEvent::with('subject.project')->each(function (SubjectEvent $subjectEvent): void {
            $project = $subjectEvent->subject->project->load('members');
            Specimentype::where('project_id', $project->id)->where('primary', true)->each(function (Specimentype $specimenType) use ($subjectEvent, $project): void {
                Specimen::factory()
                    ->count($specimenType->aliquots)
                    ->for($subjectEvent)
                    ->for($specimenType)
                    ->sequence(function (Sequence $sequence): array {
                        return [
                            'aliquot' => $sequence->index + 1,
                        ];
                    })
                    ->create([
                        'site_id' => $subjectEvent->subject->site_id,
                        'volume' => $specimenType->defaultVolume,
                        'volumeUnit' => $specimenType->volumeUnit,
                        'loggedBy' => fake()->randomElement($project->members->pluck('id')),
                        'loggedAt' => now(),
                        'status' => SpecimenStatus::Logged,
                    ]);
            });
        });
    }
}
