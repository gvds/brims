<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class ProjectMemberPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('View:Project');
    }

    public function view(AuthUser $authUser): bool
    {
        return $authUser->can('View:Project');
    }

    // public function create(AuthUser $authUser): bool
    // {
    //     return $authUser->can('Create:Project');
    // }

    // public function update(AuthUser $authUser): bool
    // {
    //     return $authUser->can('Update:Project');
    // }

    // public function delete(AuthUser $authUser): bool
    // {
    //     return $authUser->can('Delete:Project');
    // }

    public function attach(AuthUser $authUser): bool
    {
        return $authUser->can('Attach:ProjectMember');
    }

    public function detach(AuthUser $authUser): bool
    {
        return $authUser->can('Detach:ProjectMember');
    }

    public function setSubstitute(
        AuthUser $authUser,
        ProjectMember $projectMember,
        Project $project,
        ?User $substitute = null,
    ): bool
    {
        if ((string) $projectMember->project_id !== (string) $project->getKey()) {
            return false;
        }

        $member = $projectMember->user;

        if (! $member?->can('Manage:Subject')) {
            return false;
        }

        if (! $project->members()->whereKey($authUser->getAuthIdentifier())->exists()) {
            return false;
        }

        $isMemberManagingTheirOwnSubstitute =
            (string) $authUser->getAuthIdentifier() === (string) $projectMember->user_id
            && $authUser->can('Manage:Subject');

        $isProjectLeader = (string) $project->leader_id === (string) $authUser->getAuthIdentifier();

        $adminRoleId = $project->roles()->where('name', 'Admin')->value('id');
        $isProjectAdmin = $adminRoleId !== null
            && $project->members()
                ->whereKey($authUser->getAuthIdentifier())
                ->wherePivot('role_id', $adminRoleId)
                ->exists();

        if (! $isMemberManagingTheirOwnSubstitute && ! $isProjectLeader && ! $isProjectAdmin) {
            return false;
        }

        if ($substitute === null) {
            return true;
        }

        return $projectMember->site_id !== null
            && (string) $substitute->getKey() !== (string) $projectMember->user_id
            && $substitute->can('Manage:Subject')
            && $project->members()
                ->whereKey($substitute->getKey())
                ->wherePivot('site_id', $projectMember->site_id)
                ->exists();
    }
}
