<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\SystemRoles;
use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Team;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return in_array($authUser->system_role, [SystemRoles::SuperAdmin, SystemRoles::SysAdmin]);
    }

    public function view(AuthUser $authUser, Team $team): bool
    {
        return in_array($authUser->system_role, [SystemRoles::SuperAdmin, SystemRoles::SysAdmin]);
    }

    public function create(AuthUser $authUser): bool
    {
        return in_array($authUser->system_role, [SystemRoles::SuperAdmin, SystemRoles::SysAdmin]);
    }

    public function update(AuthUser $authUser, Team $team): bool
    {
        return in_array($authUser->system_role, [SystemRoles::SuperAdmin, SystemRoles::SysAdmin]);
    }

    public function delete(AuthUser $authUser, Team $team): bool
    {
        return in_array($authUser->system_role, [SystemRoles::SuperAdmin, SystemRoles::SysAdmin]);
    }
}
