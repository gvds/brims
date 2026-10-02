<?php

declare(strict_types=1);

use App\Enums\SystemRoles;
use App\Filament\Project\Resources\Roles\Pages\CreateRole;
use App\Filament\Project\Resources\Roles\Pages\EditRole;
use App\Models\Project;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\PermissionRegistrar;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

it('creates a role and syncs selected permissions using the role guard', function (): void {
    $user = User::factory()->create([
        'system_role' => SystemRoles::SuperAdmin,
    ]);
    $project = Project::factory()
        ->for(Team::factory()->create())
        ->for($user, 'leader')
        ->create();

    actingAs($user);
    Session::put('currentProject', $project);
    Filament::setCurrentPanel('project');
    Filament::setTenant($project);
    Filament::bootCurrentPanel();
    resolve(PermissionRegistrar::class)->setPermissionsTeamId($project->id);

    livewire(CreateRole::class)
        ->fillForm([
            'name' => 'Permission Test Role',
            'custom_permissions_tab' => ['Attach:ProjectMember'],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $role = Role::query()
        ->where('name', 'Permission Test Role')
        ->firstOrFail();

    expect($role->guard_name)->toBe('web')
        ->and($role->hasPermissionTo('Attach:ProjectMember'))->toBeTrue();

    $role->syncPermissions([]);

    livewire(EditRole::class, ['record' => $role->getKey()])
        ->fillForm([
            'custom_permissions_tab' => ['Attach:ProjectMember'],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($role->refresh()->hasPermissionTo('Attach:ProjectMember'))->toBeTrue();
});
