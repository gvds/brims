<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class RolePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'View:Role');
    }

    public function view(AuthUser $authUser, Role $role): bool
    {
        return evaluate_permission($authUser, 'View:Role');
    }

    public function create(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Manage:Role');
    }

    public function update(AuthUser $authUser, Role $role): bool
    {
        return evaluate_permission($authUser, 'Manage:Role');
    }

    public function delete(AuthUser $authUser, Role $role): bool
    {
        return evaluate_permission($authUser, 'Delete:Role');
    }
}
