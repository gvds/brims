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
        session()->get('currentProject') ? $builder->whereRelation('specimenType', 'project_id', session()->get('currentProject')->id) : $builder;
        if (!Auth::user() || Auth::user()->system_role === SystemRoles::SuperAdmin) return;
        if (session()->get('currentProject')) {
            session()->get('currentProject')->members->where('id', Auth::id())->count() > 0 ? $builder->where('site_id', session()->get('currentProject')->members->where('id', Auth::id())->first()->pivot->site_id) : $builder;
        }
    }
}
