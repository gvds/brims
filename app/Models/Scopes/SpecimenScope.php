<?php

namespace App\Models\Scopes;

use App\Enums\SystemRoles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class SpecimenScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $project = session('currentProject');

        if ($project) {
            $builder->whereRelation('specimenType', 'project_id', $project->getKey());
        }

        $user = Auth::user();

        if (! $project || ! $user || $user->system_role === SystemRoles::SuperAdmin) {
            return;
        }

        $membership = $project->members()
            ->whereKey($user->getAuthIdentifier())
            ->first();

        if ($membership) {
            $builder->where($model->qualifyColumn('site_id'), $membership->pivot->site_id);
        }
    }
}
