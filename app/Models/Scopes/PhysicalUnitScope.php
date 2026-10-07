<?php

namespace App\Models\Scopes;

use App\Enums\SystemRoles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class PhysicalUnitScope implements Scope
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

        if (in_array($user->system_role, [SystemRoles::SuperAdmin, SystemRoles::SysAdmin], true)) {
            return;
        }

        $institutionId = $user->team?->institution_id;

        if (! $institutionId) {
            $builder->whereKey([]);

            return;
        }

        $builder->where($model->qualifyColumn('institution_id'), $institutionId);
    }
}
