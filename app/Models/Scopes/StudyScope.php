<?php

namespace App\Models\Scopes;

use App\Enums\SystemRoles;
use App\Enums\TeamRoles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class StudyScope implements Scope
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

        if ($user->system_role === SystemRoles::SuperAdmin) {
            return;
        }

        if ($user->team_role === TeamRoles::Admin->value) {
            $builder->where($model->qualifyColumn('team_id'), $user->team_id);

            return;
        }

        $builder->whereHas(
            'members',
            fn(Builder $query) => $query->where('users.id', $user->id)
        );
    }
}
