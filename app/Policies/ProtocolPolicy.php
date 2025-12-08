<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\SystemRoles;
use App\Enums\TeamRoles;
use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Protocol;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProtocolPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return in_array($authUser->system_role, [SystemRoles::SuperAdmin, SystemRoles::SysAdmin, TeamRoles::Admin]);
        return $authUser->can('ViewAny:Protocol');
    }

    public function view(AuthUser $authUser, Protocol $protocol): bool
    {
        return in_array($authUser->system_role, [SystemRoles::SuperAdmin, SystemRoles::SysAdmin, TeamRoles::Admin]);
        return $authUser->can('View:Protocol');
    }

    public function create(AuthUser $authUser): bool
    {
        return in_array($authUser->system_role, [SystemRoles::SuperAdmin, SystemRoles::SysAdmin, TeamRoles::Admin]);
        return $authUser->can('Create:Protocol');
    }

    public function update(AuthUser $authUser, Protocol $protocol): bool
    {
        return in_array($authUser->system_role, [SystemRoles::SuperAdmin, SystemRoles::SysAdmin, TeamRoles::Admin]);
        return $authUser->can('Update:Protocol');
    }

    public function delete(AuthUser $authUser, Protocol $protocol): bool
    {
        return in_array($authUser->system_role, [SystemRoles::SuperAdmin, SystemRoles::SysAdmin, TeamRoles::Admin]);
        return $authUser->can('Delete:Protocol');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return in_array($authUser->system_role, [SystemRoles::SuperAdmin, SystemRoles::SysAdmin, TeamRoles::Admin]);
        return $authUser->can('DeleteAny:Protocol');
    }
}
