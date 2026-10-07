<?php

use App\Enums\SystemRoles;
use App\Enums\TeamRoles;
use App\Filament\App\Resources\Teams\Pages\EditTeam;
use App\Filament\App\Resources\Teams\RelationManagers\ProjectsRelationManager;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;

it('can render the projects relation manager', function (): void {
    $team = Team::factory()->create();
    $user = User::factory()->create(['team_id' => $team->id]);

    $this->actingAs($user);

    Livewire::test(ProjectsRelationManager::class, [
        'ownerRecord' => $team,
        'pageClass' => EditTeam::class,
    ])->assertSuccessful();
});

it('limits project leaders to the owning team and system administrators when creating a project', function (): void {
    $team = Team::factory()->create();
    $teamAdmin = User::factory()->create([
        'team_id' => $team->id,
        'team_role' => TeamRoles::Admin,
        'system_role' => SystemRoles::SuperAdmin,
    ]);
    $teamMember = User::factory()->create(['team_id' => $team->id]);
    $systemAdmin = User::factory()->create(['system_role' => SystemRoles::SysAdmin]);
    $superAdmin = User::factory()->create(['system_role' => SystemRoles::SuperAdmin]);
    $otherTeamMember = User::factory()->create();

    $this->actingAs($teamAdmin);
    Filament::setCurrentPanel('app');
    Filament::bootCurrentPanel();

    $component = Livewire::test(ProjectsRelationManager::class, [
        'ownerRecord' => $team,
        'pageClass' => EditTeam::class,
    ])->mountTableAction('create');

    $leaderOptions = $component->instance()
        ->getMountedTableActionForm()
        ->getComponent('leader_id')
        ->getOptionsFromRelationship();

    expect(array_keys($leaderOptions))
        ->toContain($teamMember->id, $systemAdmin->id, $superAdmin->id)
        ->not->toContain($otherTeamMember->id);
});

it('shows REDCap linked icon state for projects', function (): void {
    $team = Team::factory()->create();

    $admin = User::factory()->create([
        'team_id' => $team->id,
        'team_role' => TeamRoles::Admin,
    ]);

    // project without a redcapProject_id (null)
    $projectNull = Project::factory()->create([
        'team_id' => $team->id,
        'redcapProject_id' => null,
        'leader_id' => $admin->id,
        'title' => 'No REDCap',
    ]);

    // project with a redcapProject_id
    $projectLinked = Project::factory()->create([
        'team_id' => $team->id,
        'redcapProject_id' => 123,
        'leader_id' => $admin->id,
        'title' => 'Has REDCap',
    ]);

    $this->actingAs($admin);

    $component = Livewire::test(ProjectsRelationManager::class, [
        'ownerRecord' => $team,
        'pageClass' => EditTeam::class,
    ])
        ->assertCanSeeTableRecords([$projectNull, $projectLinked])
        ->assertTableColumnStateSet('redcapProject_id', false, $projectNull)
        ->assertTableColumnStateSet('redcapProject_id', true, $projectLinked);
});
