<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\SystemRoles;
use App\Enums\TeamRoles;
use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Project;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        // $team_id = explode('/', request()->getPathInfo())[2];
        return true;
        return $authUser->can('ViewAny:Project');
    }

    public function view(AuthUser $authUser, Project $project): bool
    {
        return $project->members->contains('id', $authUser->id);
        return $authUser->can('View:Project');
    }

    public function create(AuthUser $authUser): bool
    {
        if (Filament::getCurrentPanel()->getId() === 'app' && $authUser->team_role == TeamRoles::Admin->value || $authUser->system_role === SystemRoles::SysAdmin->value) {
            return true;
        }
        return $authUser->can('Create:Project');
    }

    public function update(AuthUser $authUser, Project $project): bool
    {
        if (Filament::getCurrentPanel()->getId() === 'app' && $authUser->team_role == TeamRoles::Admin->value || $authUser->system_role === SystemRoles::SysAdmin->value) {
            return true;
        }
        return $authUser->can('Update:Project');
    }

    public function delete(AuthUser $authUser, Project $project): bool
    {
        if (Filament::getCurrentPanel()->getId() === 'app' && $authUser->team_role == TeamRoles::Admin->value || $authUser->system_role === SystemRoles::SysAdmin->value) {
            return true;
        }
        return $authUser->can('Delete:Project');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Project');
    }

    public function reorder(AuthUser $authUser, Project $project): bool
    {
        return $authUser->can('Reorder:Project');
    }
}
