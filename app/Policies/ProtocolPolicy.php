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
        return true;
        // $authUser->can('ViewAny:Protocol')
    }

    public function view(AuthUser $authUser, Protocol $protocol): bool
    {
        return $authUser->can('View:Protocol') || $authUser->system_role === SystemRoles::SysAdmin->value || $authUser->team_role === TeamRoles::Admin->value;
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Protocol') || $authUser->system_role === SystemRoles::SysAdmin->value || $authUser->team_role === TeamRoles::Admin->value;
    }

    public function update(AuthUser $authUser, Protocol $protocol): bool
    {
        return $authUser->can('Update:Protocol') || $authUser->system_role === SystemRoles::SysAdmin->value || $authUser->team_role === TeamRoles::Admin->value;
    }

    public function delete(AuthUser $authUser, Protocol $protocol): bool
    {
        return $authUser->can('Delete:Protocol') || $authUser->system_role === SystemRoles::SysAdmin->value || $authUser->team_role === TeamRoles::Admin->value;
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Protocol') || $authUser->system_role === SystemRoles::SysAdmin->value || $authUser->team_role === TeamRoles::Admin->value;
    }
}
