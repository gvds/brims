<?php

use App\Models\Team;
use App\Models\User;
use Database\Seeders\ProjectSeeder;

it('repairs missing team leader before creating projects', function (): void {
    $team = Team::factory()->create([
        'leader_id' => null,
    ]);

    User::factory()->create([
        'team_id' => $team->id,
        'team_role' => 'Member',
    ]);

    $this->seed(ProjectSeeder::class);

    $team->refresh();

    expect($team->leader_id)->not->toBeNull();
    expect($team->projects()->count())->toBe(3);
    expect($team->projects()->whereNull('leader_id')->count())->toBe(0);
});
