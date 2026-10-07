<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\AssayDefinition;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class AssayDefinitionPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'View:AssayDefinition');
    }

    public function view(AuthUser $authUser, AssayDefinition $assayDefinition): bool
    {
        return evaluate_permission($authUser, 'View:AssayDefinition');
    }

    public function create(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Create:AssayDefinition');
    }

    public function update(AuthUser $authUser, AssayDefinition $assayDefinition): bool
    {
        return evaluate_permission($authUser, 'Manage:AssayDefinition');
    }

    public function delete(AuthUser $authUser, AssayDefinition $assayDefinition): bool
    {
        return evaluate_permission($authUser, 'Delete:AssayDefinition');
    }
}
