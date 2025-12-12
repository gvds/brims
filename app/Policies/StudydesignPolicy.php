<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\SystemRoles;
use App\Enums\TeamRoles;
use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Studydesign;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudydesignPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return true;
        // return in_array($authUser->system_role, [SystemRoles::SuperAdmin, SystemRoles::SysAdmin]);
        // return $authUser->can('ViewAny:Studydesign');
    }

    public function view(AuthUser $authUser, Studydesign $studydesign): bool
    {
        return $authUser->can('View:Studydesign') || $authUser->system_role === SystemRoles::SysAdmin->value || $authUser->team_role === TeamRoles::Admin->value;
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Studydesign') || $authUser->system_role === SystemRoles::SysAdmin->value || $authUser->team_role === TeamRoles::Admin->value;
    }

    public function update(AuthUser $authUser, Studydesign $studydesign): bool
    {
        return $authUser->can('Update:Studydesign') || $authUser->system_role === SystemRoles::SysAdmin->value || $authUser->team_role === TeamRoles::Admin->value;
    }

    public function delete(AuthUser $authUser, Studydesign $studydesign): bool
    {
        return $authUser->can('Delete:Studydesign') || $authUser->system_role === SystemRoles::SysAdmin->value || $authUser->team_role === TeamRoles::Admin->value;
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Studydesign') || $authUser->system_role === SystemRoles::SysAdmin->value || $authUser->team_role === TeamRoles::Admin->value;
    }
    public function reorder(AuthUser $authUser, Studydesign $studydesign): bool
    {
        return $authUser->can('Reorder:Studydesign') || $authUser->system_role === SystemRoles::SysAdmin->value || $authUser->team_role === TeamRoles::Admin->value;
    }
}
