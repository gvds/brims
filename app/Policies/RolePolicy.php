<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'ViewAny:Role');
    }

    public function view(AuthUser $authUser, Role $role): bool
    {
        return evaluate_permission($authUser, 'View:Role');
    }

    public function create(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Create:Role');
    }

    public function update(AuthUser $authUser, Role $role): bool
    {
        return evaluate_permission($authUser, 'Update:Role');
    }

    public function delete(AuthUser $authUser, Role $role): bool
    {
        return evaluate_permission($authUser, 'Delete:Role');
    }

    public function reorder(AuthUser $authUser, Role $role): bool
    {
        return evaluate_permission($authUser, 'Reorder:Role');
    }
}
