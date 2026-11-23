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
        return $this->isAdminOfStudyTeam($authUser, $study) ||
            $study->members()->where('user_id', $authUser->id)->exists();
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->team_role === TeamRoles::Admin->value;
    }

    public function update(AuthUser $authUser, Study $study): bool
    {
        return $this->isAdminOfStudyTeam($authUser, $study) ||
            $study->members()->where('user_id', $authUser->id)->where('role', StudyRoles::Lead)->exists();
    }

    public function delete(AuthUser $authUser, Study $study): bool
    {
        return $this->isAdminOfStudyTeam($authUser, $study);
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->team_role === TeamRoles::Admin->value;
    }

    private function isAdminOfStudyTeam(AuthUser $authUser, Study $study): bool
    {
        return $authUser->team_role === TeamRoles::Admin->value && $authUser->team_id === $study->team_id;
    }
}
