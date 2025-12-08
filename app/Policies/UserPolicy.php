<?php

namespace App\Policies;

use App\Enums\SystemRoles;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return in_array($authUser->system_role, [SystemRoles::SuperAdmin, SystemRoles::SysAdmin]);
        return $authUser->can('ViewAny:User');
    }

    public function view(AuthUser $authUser): bool
    {
        return in_array($authUser->system_role, [SystemRoles::SuperAdmin, SystemRoles::SysAdmin]);
        return $authUser->can('View:User');
    }

    public function create(AuthUser $authUser): bool
    {
        return in_array($authUser->system_role, [SystemRoles::SuperAdmin, SystemRoles::SysAdmin]);
        return $authUser->can('Create:User');
    }

    public function update(AuthUser $authUser): bool
    {
        return in_array($authUser->system_role, [SystemRoles::SuperAdmin, SystemRoles::SysAdmin]);
        return $authUser->can('Update:User');
    }

    public function delete(AuthUser $authUser): bool
    {
        return in_array($authUser->system_role, [SystemRoles::SuperAdmin, SystemRoles::SysAdmin]);
        return $authUser->can('Delete:User');
    }
}
