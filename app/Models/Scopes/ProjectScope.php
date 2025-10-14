<?php

namespace App\Models\Scopes;

use App\Enums\SystemRoles;
use App\Models\ProjectMember;
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
        if (!auth()->user() || auth()->user()->system_role === SystemRoles::SuperAdmin) return;

        $userProjectIDs = ProjectMember::where('user_id', auth()->id())->pluck('project_id');

        // $builder->where('team_id', auth()->user()->team_id)->orWhereIn('id', $userProjectIDs);
        $builder->where('team_id', auth()->user()->team_id);

        session()->get('currentProject') ? $builder->where('projects.id', session()->get('currentProject')?->id) : $builder;
    }
}
