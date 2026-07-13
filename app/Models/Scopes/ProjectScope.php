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
        session()->get('currentProject') ? $builder->where('projects.id', session()->get('currentProject')?->id) : $builder;

        if (!Auth::check() || in_array(Auth::user()?->system_role, [SystemRoles::SuperAdmin, SystemRoles::SysAdmin])) return;

        $userProjectIDs = ProjectMember::where('user_id', Auth::id())->pluck('project_id');

        $builder->where('team_id', Auth::user()->team_id)->orWhereIn('projects.id', $userProjectIDs);
    }
}
