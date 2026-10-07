<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Protocol;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class ProtocolPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return true;
        // $authUser->can('ViewAny:Protocol')
    }

    public function view(AuthUser $authUser, Protocol $protocol): bool
    {
        return evaluate_permission($authUser, 'View:Protocol');
    }

    public function create(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Manage:Protocol');
    }

    public function update(AuthUser $authUser, Protocol $protocol): bool
    {
        return evaluate_permission($authUser, 'Manage:Protocol');
    }

    public function delete(AuthUser $authUser, Protocol $protocol): bool
    {
        return evaluate_permission($authUser, 'Delete:Protocol');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Delete:Protocol');
    }
}
