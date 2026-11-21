<?php

declare(strict_types=1);

use App\Enums\SystemRoles;
use App\Enums\TeamRoles;
use App\Models\Permission;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Filament\Facades\Filament;
use Spatie\Permission\PermissionRegistrar;

use function Pest\Laravel\actingAs;

beforeEach(function (): void {
    $this->project = Project::factory()
        ->for(Team::factory())
        ->hasSites(1)
        ->create(['leader_id' => $this->adminuser->id]);

    $this->member = User::factory()->create([
        'system_role' => SystemRoles::User,
        'team_id' => Team::factory(),
        'team_role' => TeamRoles::Member,
    ]);

    $this->role = $this->project->roles()->create(['name' => 'Scheduler']);

    $this->project->members()->attach($this->member, [
        'role_id' => $this->role->id,
        'site_id' => $this->project->sites->first()->id,
    ]);

    setPermissionsTeamId($this->project->id);
    $this->member->syncRoles($this->role);
    setPermissionsTeamId(null);
    $this->member->unsetRelation('roles')->unsetRelation('permissions');

    actingAs($this->member);
    Filament::setCurrentPanel('app');
    Filament::bootCurrentPanel();
});

it('lets a member whose project role has the Manage:Subject permission view the calendar and its navigation link', function (): void {
    $this->role->givePermissionTo(Permission::firstOrCreate(['name' => 'Manage:Subject']));
    resolve(PermissionRegistrar::class)->forgetCachedPermissions();

    $this->get(route('filament.app.pages.calendar'))
        ->assertOk()
        ->assertSee('href="'.route('filament.app.pages.calendar').'"', false);
});

it('denies a member whose project role lacks the Manage:Subject permission the calendar and its navigation link', function (): void {
    $this->get(route('filament.app.pages.calendar'))
        ->assertForbidden();

    $this->get(route('filament.app.pages.dashboard'))
        ->assertOk()
        ->assertDontSee('href="'.route('filament.app.pages.calendar').'"', false);
});
