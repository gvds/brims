<?php

declare(strict_types=1);

use App\Enums\SystemRoles;
use App\Enums\TeamRoles;
use App\Filament\App\Resources\Projects\Pages\ListProjects;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->memberTeam = Team::factory()->create();
    $this->projectTeam = Team::factory()->create();

    $this->member = User::factory()->create([
        'system_role' => SystemRoles::User,
        'team_id' => $this->memberTeam->id,
        'team_role' => TeamRoles::Member,
    ]);

    $this->memberProjects = Project::factory()
        ->count(2)
        ->for($this->projectTeam)
        ->create(['leader_id' => $this->adminuser->id]);

    foreach ($this->memberProjects as $project) {
        $project->members()->attach($this->member->id, ['role_id' => 'Member']);
    }

    $this->otherProject = Project::factory()
        ->for($this->projectTeam)
        ->create(['leader_id' => $this->adminuser->id]);

    actingAs($this->member);
    Filament::setCurrentPanel('app');
    Filament::bootCurrentPanel();
});

it('lists every project a non team leader user is a member of', function (): void {
    livewire(ListProjects::class)
        ->assertOk()
        ->assertCanSeeTableRecords($this->memberProjects)
        ->assertCountTableRecords(2);
});

it('does not list projects the user is not a member of', function (): void {
    livewire(ListProjects::class)
        ->assertCanNotSeeTableRecords([$this->otherProject]);
});

it('lists all projects of their own team plus projects they are a member of for a team admin', function (): void {
    $teamAdmin = User::factory()->create([
        'system_role' => SystemRoles::User,
        'team_id' => $this->memberTeam->id,
        'team_role' => TeamRoles::Admin,
    ]);
    $ownTeamProject = Project::factory()
        ->for($this->memberTeam)
        ->create(['leader_id' => $this->adminuser->id]);
    $this->memberProjects->first()->members()->attach($teamAdmin->id, ['role_id' => 'Member']);

    actingAs($teamAdmin);

    livewire(ListProjects::class)
        ->assertCanSeeTableRecords([$ownTeamProject, $this->memberProjects->first()])
        ->assertCanNotSeeTableRecords([$this->otherProject, $this->memberProjects->last()]);
});
