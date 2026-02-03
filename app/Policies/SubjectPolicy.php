<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\SystemRoles;
use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Subject;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

class SubjectPolicy
{
    use HandlesAuthorization;

    private function evaluateModelPermission($authUser, string $permission, Model $model): bool
    {
        $userIDList = $authUser->substitutees()
            ->where('project_id', session('currentProject')->id)
            ->pluck('users.id')
            ->push($authUser->id)
            ->toArray();

        $conditions = [
            $authUser->system_role === SystemRoles::SysAdmin,
            Filament::getCurrentPanel()->getId() === 'app' && $authUser->is_team_admin,
            $authUser->is_team_admin && session('currentProject')?->team_id === $authUser->team_id,
            $authUser->can($permission) && in_array($model->user_id, $userIDList),
        ];

        return in_array(true, $conditions, true);
    }

    public function viewAny(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'ViewAny:Subject');
    }

    public function view(AuthUser $authUser, Subject|string $subject): bool
    {
        if (is_string($subject)) {
            return evaluate_permission($authUser, 'View:Subject');
        }

        return $this->evaluateModelPermission($authUser, 'View:Subject', $subject) && $subject->status !== \App\Enums\SubjectStatus::Generated;
    }

    public function create(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Create:Subject');
    }

    public function update(AuthUser $authUser, Subject|string $subject): bool
    {
        if (is_string($subject)) {
            return evaluate_permission($authUser, 'Update:Subject');
        }

        return $this->evaluateModelPermission($authUser, 'Update:Subject', $subject);
    }

    public function delete(AuthUser $authUser, Subject|string $subject): bool
    {
        if (is_string($subject)) {
            return evaluate_permission($authUser, 'Delete:Subject');
        }

        return $this->evaluateModelPermission($authUser, 'Delete:Subject', $subject);
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'DeleteAny:Subject');
    }
}
