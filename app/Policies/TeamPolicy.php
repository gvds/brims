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
        return true;
    }

    public function view(AuthUser $authUser, Team $team): bool
    {
        return $authUser->system_role === SystemRoles::SysAdmin || $authUser->team_id === $team->id;
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->system_role === SystemRoles::SysAdmin;
    }

    public function update(AuthUser $authUser, Team $team): bool
    {
        return $authUser->system_role === SystemRoles::SysAdmin || ($authUser->team_id === $team->id && $authUser->team_role === 'Admin');
    }

    public function delete(AuthUser $authUser, Team $team): bool
    {
        return $authUser->system_role === SystemRoles::SysAdmin;
    }
}
