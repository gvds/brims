<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\SystemRoles;
use App\Enums\TeamRoles;
use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\AssayDefinition;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssayDefinitionPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return true;
        // return $authUser->can('ViewAny:AssayDefinition');
    }

    public function view(AuthUser $authUser, AssayDefinition $assayDefinition): bool
    {
        return true;
        // return $authUser->can('View:AssayDefinition');
    }

    public function create(AuthUser $authUser): bool
    {

        return $authUser->can('Create:AssayDefinition') || $authUser->system_role === SystemRoles::SysAdmin->value || $authUser->team_role === TeamRoles::Admin->value;
    }

    public function update(AuthUser $authUser, AssayDefinition $assayDefinition): bool
    {
        return $authUser->can('Update:AssayDefinition') || $authUser->system_role === SystemRoles::SysAdmin->value || $authUser->team_role === TeamRoles::Admin->value;
    }

    public function delete(AuthUser $authUser, AssayDefinition $assayDefinition): bool
    {
        return $authUser->can('Delete:AssayDefinition') || $authUser->system_role === SystemRoles::SysAdmin->value || $authUser->team_role === TeamRoles::Admin->value;
    }

    public function reorder(AuthUser $authUser, AssayDefinition $assayDefinition): bool
    {
        return $authUser->can('Reorder:AssayDefinition') || $authUser->system_role === SystemRoles::SysAdmin->value || $authUser->team_role === TeamRoles::Admin->value;
    }
}
