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
        return evaluate_permission($authUser, 'Create:AssayDefinition');
    }

    public function update(AuthUser $authUser, AssayDefinition $assayDefinition): bool
    {
        return evaluate_permission($authUser, 'Update:AssayDefinition');
    }

    public function delete(AuthUser $authUser, AssayDefinition $assayDefinition): bool
    {
        return evaluate_permission($authUser, 'Delete:AssayDefinition');
    }

    public function reorder(AuthUser $authUser, AssayDefinition $assayDefinition): bool
    {
        return evaluate_permission($authUser, 'Reorder:AssayDefinition');
    }
}
