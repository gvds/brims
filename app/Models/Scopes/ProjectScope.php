<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ProjectScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (!auth()->user() || auth()->user()->hasRole('super_admin')) return;

        session()->get('currentProject') ? $builder->where('projects.id', session()->get('currentProject')?->id) : $builder;
    }
}
