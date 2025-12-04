<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Protocol;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProtocolPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Protocol');
    }

    public function view(AuthUser $authUser, Protocol $protocol): bool
    {
        return $authUser->can('View:Protocol');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Protocol');
    }

    public function update(AuthUser $authUser, Protocol $protocol): bool
    {
        return $authUser->can('Update:Protocol');
    }

    public function delete(AuthUser $authUser, Protocol $protocol): bool
    {
        return $authUser->can('Delete:Protocol');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Protocol');
    }

    public function reorder(AuthUser $authUser, Protocol $protocol): bool
    {
        return $authUser->can('Reorder:Protocol');
    }
}
