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
        SubjectEvent::with('subject.project')->where('status', 3)->each(function (SubjectEvent $subjectEvent): void {
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
                        'loggedBy_id' => fake()->randomElement($project->members->pluck('id')),
                        'loggedAt' => now(),
                        'usedBy_id' => fake()->randomElement($project->members->pluck('id')),
                        'usedAt' => now(),
                        'status' => SpecimenStatus::Used,
                    ]);
            });

            Specimentype::with('parentSpecimenType')->where('project_id', $project->id)->where('primary', false)->each(function (Specimentype $specimenType) use ($subjectEvent, $project): void {
                $parentSpecimenType = $specimenType->parentSpecimenType;

                if ($parentSpecimenType->pooled) {
                    $parentSpecimens = Specimen::where('specimenType_id', $parentSpecimenType->id)->where('subject_event_id', $subjectEvent->id)->take(1)->get();
                } else {
                    $parentSpecimens = Specimen::where('specimenType_id', $parentSpecimenType->id)->where('subject_event_id', $subjectEvent->id)->get();
                }
                $parentSpecimens->each(function (Specimen $parentSpecimen) use ($subjectEvent, $specimenType, $project): void {
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
                            'loggedBy_id' => fake()->randomElement($project->members->pluck('id')),
                            'loggedAt' => now(),
                            'status' => SpecimenStatus::Logged,
                            'parentSpecimen_id' => $parentSpecimen->id,
                        ]);
                });
            });
        });
    }
}
