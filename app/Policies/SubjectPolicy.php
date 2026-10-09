<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\SubjectStatus;
use App\Enums\SystemRoles;
use App\Models\Subject;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as AuthUser;

class SubjectPolicy
{
    use HandlesAuthorization;

    private function evaluateModelPermission(AuthUser $authUser, string $permission, Model $model): bool
    {
        $userIDList = $authUser->substitutees()
            ->where('project_id', session('currentProject')?->id)
            ->pluck('users.id')
            ->push($authUser->id)
            ->toArray();

        $conditions = [
            $authUser->system_role === SystemRoles::SysAdmin,
            $authUser->is_team_admin && session('currentProject')?->team_id === $authUser->team_id,
            $authUser->can($permission) && in_array($model->user_id, $userIDList),
        ];

        return in_array(true, $conditions, true);
    }

    public function viewAny(AuthUser $authUser): bool
    {
        return (evaluate_permission($authUser, 'View:Subject'));
    }

    public function view(AuthUser $authUser, Subject $subject): bool
    {
        return $this->evaluateModelPermission($authUser, 'View:Subject', $subject);
    }

    public function create(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Manage:Subject');
    }

    public function update(AuthUser $authUser, Subject $subject): bool
    {
        return $this->evaluateModelPermission($authUser, 'Manage:Subject', $subject) && $subject->status !== SubjectStatus::Generated;
    }

    public function delete(AuthUser $authUser, Subject $subject): bool
    {
        return $this->evaluateModelPermission($authUser, 'Delete:Subject', $subject);
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Delete:Subject');
    }
}
