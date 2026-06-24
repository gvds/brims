<?php


use App\Models\LabelSpecification;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('can resolve the label specification relation using label_format as foreign key', function (): void {
    $spec = LabelSpecification::updateOrCreate([
        'format' => 'TEST99',
    ], [
        'paper-size' => 'A4',
        'metric' => false,
        'marginLeft' => 0,
        'marginTop' => 0,
        'NX' => 1,
        'NY' => 1,
        'SpaceX' => 0,
        'SpaceY' => 0,
        'width' => 100,
        'height' => 50,
        'font-size' => 8,
        'padding' => 0,
    ]);

    $team = Team::factory()->create();
    $leader = User::factory()->create();

    $project = Project::factory()->for($team)->create([
        'leader_id' => $leader->id,
        'label_format' => $spec->format,
    ]);

    expect($project->labelSpecification)->not->toBeNull();
    expect($project->labelSpecification->format)->toBe($spec->format);
});
