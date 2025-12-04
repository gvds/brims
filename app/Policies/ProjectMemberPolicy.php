<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class ProjectMemberPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ProjectMember');
    }

    public function view(AuthUser $authUser): bool
    {
        return $authUser->can('View:ProjectMember');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ProjectMember');
    }

    public function update(AuthUser $authUser): bool
    {
        return $authUser->can('Update:ProjectMember');
    }

    public function delete(AuthUser $authUser): bool
    {
        return $authUser->can('Delete:ProjectMember');
    }

    public function attach(AuthUser $authUser): bool
    {
        return $authUser->can('Attach:ProjectMember');
    }

    public function detach(AuthUser $authUser): bool
    {
        return $authUser->can('Detach:ProjectMember');
    }

    public function setSubstitute(AuthUser $authUser): bool
    {
        return $authUser->can('SetSubstitute:ProjectMember');
    }
}
