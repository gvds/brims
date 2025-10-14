<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Project;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return true;
        return $authUser->can('ViewAny:Project');
    }

    public function view(AuthUser $authUser, Project $project): bool
    {
        return true;
        return $authUser->can('View:Project');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Project');
    }

    public function update(AuthUser $authUser, Project $project): bool
    {
        return $authUser->can('Update:Project');
    }

    public function delete(AuthUser $authUser, Project $project): bool
    {
        return $authUser->can('Delete:Project');
    }

    public function reorder(AuthUser $authUser, Project $project): bool
    {
        return $authUser->can('Reorder:Project');
    }
}
