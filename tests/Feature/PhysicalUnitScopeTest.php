<?php

use App\Enums\SystemRoles;
use App\Models\Institution;
use App\Models\PhysicalUnit;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

it('scopes physical units to the authenticated users institution', function (): void {
    $institutionA = Institution::factory()->create();
    $institutionB = Institution::factory()->create();

    $teamA = Team::factory()->create(['institution_id' => $institutionA->id]);
    Team::factory()->create(['institution_id' => $institutionB->id]);

    $user = User::factory()->create([
        'team_id' => $teamA->id,
        'team_role' => 'Admin',
        'system_role' => SystemRoles::User,
    ]);

    PhysicalUnit::factory()->create(['institution_id' => $institutionA->id]);
    PhysicalUnit::factory()->create(['institution_id' => $institutionB->id]);

    Auth::login($user);

    expect(PhysicalUnit::count())->toBe(1);
});

it('does not scope physical units for system administrators', function (): void {
    $institution = Institution::factory()->create();
    $team = Team::factory()->create(['institution_id' => $institution->id]);

    $user = User::factory()->create([
        'team_id' => $team->id,
        'team_role' => 'Admin',
        'system_role' => SystemRoles::SysAdmin,
    ]);

    PhysicalUnit::factory()->create(['institution_id' => $institution->id]);
    PhysicalUnit::factory()->create(['institution_id' => $institution->id]);

    Auth::login($user);

    expect(PhysicalUnit::count())->toBe(2);
});
