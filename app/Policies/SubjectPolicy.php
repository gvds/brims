<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\SystemRoles;
use App\Models\ProjectMember;
use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Subject;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SubjectPolicy
{
    use HandlesAuthorization;

    private function evaluateModelPermission($authUser, string $permission, Model $model): bool
    {
        $userIDList = ProjectMember::where('substitute_id', Auth::id())
            ->pluck('user_id')
            ->toArray();
        array_push($userIDList, $authUser->id);

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

    public function view(AuthUser $authUser, Subject $subject): bool
    {
        return $this->evaluateModelPermission($authUser, 'View:Subject', $subject);
    }

    public function create(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Create:Subject');
    }

    public function update(AuthUser $authUser, Subject $subject): bool
    {
        return $this->evaluateModelPermission($authUser, 'Update:Subject', $subject);
    }

    public function delete(AuthUser $authUser, Subject $subject): bool
    {
        return $this->evaluateModelPermission($authUser, 'Delete:Subject', $subject);
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'DeleteAny:Subject');
    }

    public function reorder(AuthUser $authUser, Subject $subject): bool
    {
        return evaluate_permission($authUser, 'Reorder:Subject');
    }
}
