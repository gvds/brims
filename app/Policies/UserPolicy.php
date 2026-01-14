<?php

namespace App\Policies;

use App\Enums\SystemRoles;
use App\Models\User;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'ViewAny:User');
    }

    public function view(AuthUser $authUser): bool
    {

        return evaluate_permission($authUser, 'View:User');
    }

    public function create(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Create:User');
    }

    public function update(AuthUser $authUser, User $user): bool
    {
        return evaluate_permission($authUser, 'Update:User') &&
            $user->system_role !== SystemRoles::SuperAdmin;
    }

    public function delete(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Delete:User');
    }

    public function setSubstitute(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'SetSubstitute:ProjectMember');
    }

    public function attach(AuthUser $authUser): bool
    {

        return evaluate_permission($authUser, 'Attach:ProjectMember');
    }

    public function detach(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Detach:ProjectMember');
    }
}
