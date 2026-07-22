<?php

declare(strict_types=1);

use App\Enums\SystemRoles;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Session;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->team = Team::factory()->create();

    $this->superAdmin = User::factory()->create([
        'system_role' => SystemRoles::SuperAdmin,
        'team_id' => $this->team->id,
        'team_role' => 'Admin',
    ]);

    $this->team->update([
        'leader_id' => $this->superAdmin->id
    ]);

    $this->user = User::factory()->create([
        'system_role' => SystemRoles::User,
        'team_id' => $this->team->id,
        'team_role' => 'Member',
    ]);

    $this->project = Project::factory()
        ->for($this->team)
        ->hasSites(2)
        ->create([
            'leader_id' => $this->superAdmin->id,
        ]);

    $projectAdminRole = $this->project->roles()->create([
        'name' => 'Admin'
    ]);
    $projectMemberRole = $this->project->roles()->create([
        'name' => 'OrdinaryMember'
    ]);

    // $this->project->members()->attach($this->user, ['role_id' => $projectAdminRole->id]);
    $this->project->members()->attach($this->user, ['role_id' => $projectMemberRole->id]);

    // actingAs($this->superAdmin);
    actingAs($this->user);
    Session::put('currentProject', $this->project);
    Filament::setCurrentPanel('project');
    Filament::setTenant($this->project);
    Filament::bootCurrentPanel();
});

test('that page loads', function () {
    $response = $this->get('/project/' . $this->project->id);

    $response->assertStatus(200);

    $response->assertSee('Main Panel');

    $response->assertSee('Project Configuration');
});

test('that user with no permissions cannot see any project function pages', function (): void {
    $this->get('/project/' . $this->project->id)
        ->assertDontSee('Generate Schedule')
        ->assertDontSee('Label Queue')
        ->assertDontSee('Log Primary Specimens')
        ->assertDontSee('Log Derivative Specimens')
        ->assertDontSee('Specimen Storage')
        ->assertDontSee('Roles');
});

test('that user with no permissions cannot see any relationmanagers under project configuration', function (): void {
    $this->get('/project/' . $this->project->id . '/projects/' . $this->project->id)
        ->assertSee('Title')
        ->assertDontSee('Members')
        ->assertDontSee('Sites')
        ->assertDontSee('Arms')
        ->assertDontSee('Labwares')
        ->assertDontSee('Specimen Types')
        ->assertDontSee('Programmes');
});
