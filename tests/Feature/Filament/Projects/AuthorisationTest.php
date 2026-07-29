<?php

declare(strict_types=1);

use App\Enums\SubjectStatus;
use App\Enums\SystemRoles;
use App\Filament\Project\Resources\Subjects\Pages\ListSubjects;
use App\Models\Arm;
use App\Models\Permission;
use App\Models\Project;
use App\Models\Subject;
use App\Models\Team;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\PermissionRegistrar;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    Artisan::call('shield:generate --all --panel=project --option=permissions --no-interaction');

    $this->team = Team::factory()->create();
    $this->team2 = Team::factory()->create();

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
        'team_id' => $this->team2->id,
        'team_role' => 'Member',
    ]);

    $this->user3 = User::factory()->create([
        'system_role' => SystemRoles::User,
        'team_id' => $this->team->id,
        'team_role' => 'Member',
    ]);

    $this->project = Project::factory()
        ->for($this->team)
        ->hasSites(2)
        ->create([
            'title' => 'Pest Project',
            'leader_id' => $this->superAdmin->id,
            'subjectID_prefix' => 'PP',
            'subjectID_digits' => 4,
        ]);

    Arm::factory()
        ->count(1)
        ->for($this->project)
        ->sequence(fn(Sequence $sequence): array => [
            'arm_num' => $sequence->index + 1,
            'manual_enrol' => $sequence->index === 0 ? true : false,
        ])
        ->create();

    $projectAdminRole = $this->project->roles()->create([
        'name' => 'Admin'
    ]);
    $projectMemberRole = $this->project->roles()->create([
        'name' => 'OrdinaryMember'
    ]);

    // $this->project->members()->attach($this->user, ['role_id' => $projectAdminRole->id]);
    $this->project->members()->attach($this->user, ['role_id' => $projectMemberRole->id, 'site_id' => $this->project->sites->first()->id]);
    $this->project->members()->attach($this->user2, ['role_id' => $projectMemberRole->id, 'site_id' => $this->project->sites->first()->id]);

    // actingAs($this->superAdmin);
    actingAs($this->user);
    setPermissionsTeamId($this->project->id);
    Session::put('currentProject', $this->project);
    Filament::setCurrentPanel('project');
    Filament::setTenant($this->project);
    Filament::bootCurrentPanel();
});

it('can access the project panel of a project of which it is a member', function () {
    $this->get('/project/' . $this->project->id)
        ->assertStatus(200)
        ->assertSee('Main Panel')
        ->assertSee('Project Configuration');
});

it('cannot access the project panel of a project of which it is not a member', function () {
    actingAs($this->user3);
    $this->get('/project/' . $this->project->id)
        ->assertStatus(404);
});

it('can access the project panel for another teams project', function () {
    actingAs($this->user2);
    $this->get('/project/' . $this->project->id)
        ->assertStatus(200)
        ->assertSee('Main Panel')
        ->assertSee('Project Configuration');
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
    $permission = Permission::firstOrCreate(['name' => 'View:Subject']);
    $this->user->givePermissionTo($permission);
    resolve(PermissionRegistrar::class)->forgetCachedPermissions();

    $this->get('/project/' . $this->project->id)
        ->assertSee('Subjects');
});

it('can access the subjects list page given View:Subject permission', function (): void {
    $permission = Permission::firstOrCreate(['name' => 'View:Subject']);
    $this->user->givePermissionTo($permission);
    resolve(PermissionRegistrar::class)->forgetCachedPermissions();

    $this->get('/project/' . $this->project->id . '/subjects')
        ->assertOk()
        ->assertSee('Generate subjects')
        ->assertSee('Subject ID');
});

it('can view an enrolled subject given View:Subject permission', function (): void {
    $permission = Permission::firstOrCreate(['name' => 'View:Subject']);
    $this->user->givePermissionTo($permission);
    resolve(PermissionRegistrar::class)->forgetCachedPermissions();

    $subject = Subject::factory()->create([
        'project_id' => $this->project->id,
        'user_id' => $this->user->id,
        'subjectID' => 'PP0001',
        'site_id' => $this->project->sites->first()->id,
        'arm_id' => $this->project->arms->first()->id,
        'status' => SubjectStatus::Enrolled
    ]);

    $this->get('/project/' . $this->project->id . '/subjects/' . $subject->id)
        ->assertSee('View Subject')
        ->assertSee($subject->subjectID);
});

it('cannot access the create_subjects modal without Manage:Subject permission', function (): void {
    $permission = Permission::firstOrCreate(['name' => 'View:Subject']);
    $this->user->givePermissionTo($permission);
    resolve(PermissionRegistrar::class)->forgetCachedPermissions();

    actingAs($this->user);
    $this->get('/project/' . $this->project->id . '/subjects')
        ->assertOk();

    livewire(ListSubjects::class)
        ->assertActionDisabled('generate_subjects');
});

it('cannot edit an enrolled subject without Manage:Subject permission', function (): void {
    $permission = Permission::firstOrCreate(['name' => 'View:Subject']);
    $this->user->givePermissionTo($permission);
    resolve(PermissionRegistrar::class)->forgetCachedPermissions();

    $subject = Subject::factory()->create([
        'project_id' => $this->project->id,
        'user_id' => $this->user->id,
        'subjectID' => 'PP0001',
        'site_id' => $this->project->sites->first()->id,
        'arm_id' => $this->project->arms->first()->id,
        'status' => SubjectStatus::Enrolled
    ]);

    $this->get('/project/' . $this->project->id . '/subjects/' . $subject->id . '/edit')
        ->assertForbidden();
});

it('can edit an enrolled subject given Manage:Subject permission', function (): void {
    $permission = Permission::firstOrCreate(['name' => 'View:Subject']);
    $permission2 = Permission::firstOrCreate(['name' => 'Manage:Subject']);
    $this->user->givePermissionTo($permission);
    $this->user->givePermissionTo($permission2);
    resolve(PermissionRegistrar::class)->forgetCachedPermissions();

    $subject = Subject::factory()->create([
        'project_id' => $this->project->id,
        'user_id' => $this->user->id,
        'subjectID' => 'PP0001',
        'site_id' => $this->project->sites->first()->id,
        'arm_id' => $this->project->arms->first()->id,
        'status' => SubjectStatus::Enrolled
    ]);

    $this->get('/project/' . $this->project->id . '/subjects/' . $subject->id . '/edit')
        ->assertOk()
        ->assertSee('Edit Subject');
});
