<?php

namespace App\Models\Scopes;

use App\Enums\SystemRoles;
use App\Enums\TeamRoles;
use App\Models\ProjectMember;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class SubjectScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $user = Auth::user();

        if (! $user) {
            $builder->whereKey([]);
            return;
        }

        $project = session('currentProject');

        if ($project) {
            $builder->where($model->qualifyColumn('project_id'), $project->getKey());
        }

        if (! $project ||  in_array(Auth::user()->system_role, [SystemRoles::SysAdmin, SystemRoles::SuperAdmin])) {
            return;
        }

        $membership = ProjectMember::query()
            ->with('role')
            ->where('project_id', $project->getKey())
            ->where('user_id', $user->getAuthIdentifier())
            ->first();

        if (! $membership) {
            $builder->whereKey([]);
            return;
        }

        if ($membership->role?->name === 'Admin') {
            return;
        }


        $substitutees = Auth::user()->substitutees
            ->where('project_id', session('currentProject')->id)
            ->pluck('users.id');

        $builder->where(fn($q) => $q
            ->where($model->qualifyColumn('site_id'), $membership->site_id)
            ->where('user_id', Auth::id())
            ->orWhereIn('user_id', $substitutees));
    }
}
