<?php

declare(strict_types=1);

use App\Enums\SystemRoles;
use App\Models\Permission;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\PermissionRegistrar;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    Artisan::call('shield:generate --all --panel=project --option=permissions --no-interaction');

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

    $this->user2 = User::factory()->create([
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
    $this->project->members()->attach($this->user2, ['role_id' => $projectMemberRole->id]);

    // actingAs($this->superAdmin);
    actingAs($this->user);
    setPermissionsTeamId($this->project->id);
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

test('that user with no permissions cannot see any project function links', function (): void {
    $this->get('/project/' . $this->project->id)
        ->assertDontSee('Subjects')
        ->assertDontSee('Generate Schedule')
        ->assertDontSee('Label Queue')
        ->assertDontSee('Specimens')
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

it('cannot access the schedule route without Mangage:Subject permission', function (): void {
    $this->get('/schedule/thisweek')
        ->assertForbidden();
});

it('cannot access the subjects page without View:Subject permission', function (): void {
    $this->get('/project/' . $this->project->id . '/subjects')
        ->assertForbidden();
});

it('can see the subjects link given View:Subject permission', function (): void {
    // Session::put('currentProject', $this->project);

    $permission = Permission::firstOrCreate(['name' => 'View:Subject']);
    $this->user2->givePermissionTo($permission);
    resolve(PermissionRegistrar::class)->forgetCachedPermissions();

    actingAs($this->user2);
    $this->get('/project/' . $this->project->id)
        ->assertSee('Subjects');
});

it('can access the subjects list page given View:Subject permission', function (): void {
    $permission = Permission::firstOrCreate(['name' => 'View:Subject']);
    $this->user2->givePermissionTo($permission);
    resolve(PermissionRegistrar::class)->forgetCachedPermissions();

    actingAs($this->user2);
    $this->get('/project/' . $this->project->id . '/subjects')
        ->assertOk()
        ->assertSee('Subject ID');
});
