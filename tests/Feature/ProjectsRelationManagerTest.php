<?php

use App\Enums\SystemRoles;
use App\Enums\TeamRoles;
use App\Filament\App\Resources\Teams\Pages\EditTeam;
use App\Filament\App\Resources\Teams\Pages\ViewTeam;
use App\Filament\App\Resources\Teams\RelationManagers\ProjectsRelationManager;
use App\Models\Programme;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
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
    ])->mountAction(TestAction::make('create')->table());

    $leaderOptions = $component->instance()
        ->getMountedTableActionForm()
        ->getComponent('leader_id')
        ->getOptionsFromRelationship();

    expect(array_keys($leaderOptions))
        ->toContain($teamMember->id, $systemAdmin->id, $superAdmin->id)
        ->not->toContain($otherTeamMember->id);
});

it('shows programme names and funders in project programme options', function (): void {
    $team = Team::factory()->create();
    $teamAdmin = User::factory()->create([
        'team_id' => $team->id,
        'team_role' => TeamRoles::Admin->value,
    ]);
    $programme = Programme::factory()->create([
        'team_id' => $team->id,
        'user_id' => $teamAdmin->id,
        'name' => 'Example Programme',
        'funder' => 'Example Funder',
    ]);

    $this->actingAs($teamAdmin);
    Filament::setCurrentPanel('app');
    Filament::bootCurrentPanel();

    $component = Livewire::test(ProjectsRelationManager::class, [
        'ownerRecord' => $team,
        'pageClass' => ViewTeam::class,
    ])->mountAction(TestAction::make('create')->table());

    $programmeOptions = $component->instance()
        ->getMountedTableActionForm()
        ->getComponent('programmes')
        ->getOptionsFromRelationship();

    expect($programmeOptions)->toHaveKey(
        $programme->id,
        "Example Programme (Example Funder) - {$teamAdmin->fullname}",
    );
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
        'pageClass' => ViewTeam::class,
    ])
        ->assertCanSeeTableRecords([$projectNull, $projectLinked])
        ->assertTableColumnStateSet('redcapProject_id', false, $projectNull)
        ->assertTableColumnStateSet('redcapProject_id', true, $projectLinked);
});

it('shows linked programme names as a vertical list', function (): void {
    $team = Team::factory()->create();
    $admin = User::factory()->create([
        'team_id' => $team->id,
        'team_role' => TeamRoles::Admin->value,
    ]);
    $project = Project::factory()->create([
        'team_id' => $team->id,
        'leader_id' => $admin->id,
    ]);
    $programmeAttributes = [
        'team_id' => $team->id,
        'user_id' => $admin->id,
        'funder' => 'Test Funder',
    ];
    $programmeOne = Programme::factory()->create([
        ...$programmeAttributes,
        'name' => 'Programme One',
    ]);
    $programmeTwo = Programme::factory()->create([
        ...$programmeAttributes,
        'name' => 'Programme Two',
    ]);

    $project->programmes()->attach([$programmeOne->id, $programmeTwo->id]);

    $this->actingAs($admin);

    $component = Livewire::test(ProjectsRelationManager::class, [
        'ownerRecord' => $team,
        'pageClass' => ViewTeam::class,
    ])->assertCanSeeTableRecords([$project]);

    $column = $component->instance()->getTable()->getColumn('programmes.name');
    $column->record($project);

    expect($column->getState())
        ->toContain('Programme One', 'Programme Two');
    expect($column->isListWithLineBreaks())->toBeTrue();
});
