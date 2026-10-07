<?php

namespace App\Models\Scopes;

use App\Enums\SystemRoles;
use App\Models\ProjectMember;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class ProjectScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $project = session('currentProject');

        if ($project) {
            $builder->where($model->qualifyColumn('id'), $project->getKey());
        }

        $user = Auth::user();

        if (! $user || in_array($user->system_role, [SystemRoles::SuperAdmin, SystemRoles::SysAdmin], true)) {
            return;
        }

        $userProjectIds = ProjectMember::query()
            ->where('user_id', $user->getAuthIdentifier())
            ->pluck('project_id');

        $builder->where(function (Builder $query) use ($model, $user, $userProjectIds): void {
            $query->where($model->qualifyColumn('team_id'), $user->team_id)
                ->orWhereIn($model->qualifyColumn('id'), $userProjectIds);
        });
    }
}
