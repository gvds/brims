<?php

declare(strict_types=1);

use App\Enums\SystemRoles;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;

it('allows a superadmin to access any project tenant', function (): void {
    $superAdmin = User::factory()->create([
        'system_role' => SystemRoles::SuperAdmin,
    ]);

    $team = Team::factory()->create();
    $project = Project::factory()
        ->for($team)
        ->for($superAdmin, 'leader')
        ->create();

    expect($superAdmin->canAccessTenant($project))->toBeTrue();
});
