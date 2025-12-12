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
        return $authUser->system_role === SystemRoles::SysAdmin || $authUser->can('ViewAny:User');
    }

    public function view(AuthUser $authUser): bool
    {
        return $authUser->system_role === SystemRoles::SysAdmin || $authUser->can('View:User');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->system_role === SystemRoles::SysAdmin || $authUser->can('Create:User');
    }

    public function update(AuthUser $authUser): bool
    {
        return $authUser->system_role === SystemRoles::SysAdmin || $authUser->can('Update:User');
    }

    public function delete(AuthUser $authUser): bool
    {
        return $authUser->system_role === SystemRoles::SysAdmin || $authUser->can('Delete:User');
    }

    public function setSubstitute(AuthUser $authUser): bool
    {
        return $authUser->system_role === SystemRoles::SysAdmin || $authUser->can('SetSubstitute:ProjectMember');
    }

    public function attach(AuthUser $authUser): bool
    {
        return $authUser->system_role === SystemRoles::SysAdmin || $authUser->can('Attach:ProjectMember');
    }

    public function detach(AuthUser $authUser): bool
    {
        return $authUser->system_role === SystemRoles::SysAdmin || $authUser->can('Detach:ProjectMember');
    }
}
