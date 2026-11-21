<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\StudyRoles;
use App\Enums\TeamRoles;
use App\Models\Study;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class StudyPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return true;
    }

    public function view(AuthUser $authUser, Study $study): bool
    {
        return true;
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->team_role === TeamRoles::Admin;
    }

    public function update(AuthUser $authUser, Study $study): bool
    {
        return $authUser->team_role === TeamRoles::Admin ||
            $study->members()->where('user_id', $authUser->id)->where('role', StudyRoles::Lead)->exists();
    }

    public function delete(AuthUser $authUser, Study $study): bool
    {
        return $authUser->team_role === TeamRoles::Admin;
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->team_role === TeamRoles::Admin;
    }
}
