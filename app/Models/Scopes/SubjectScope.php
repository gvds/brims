<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SubjectScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        session()->get('currentProject') ? $builder->where('project_id', session()->get('currentProject')->id) : $builder;
        // if (!auth()->user() || auth()->user()->hasRole('super_admin')) return;
        // $builder->where('site_id', session()->get('currentProject')->members->where('id', auth()->id())->first()->pivot->site_id);
    }
}
