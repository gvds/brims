<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\SystemRoles;
use App\Enums\TeamRoles;
use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Protocol;
use Illuminate\Auth\Access\HandlesAuthorization;

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
        return evaluate_permission($authUser, 'Create:Protocol');
    }

    public function update(AuthUser $authUser, Protocol $protocol): bool
    {
        return evaluate_permission($authUser, 'Update:Protocol');
    }

    public function delete(AuthUser $authUser, Protocol $protocol): bool
    {
        return evaluate_permission($authUser, 'Delete:Protocol');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'DeleteAny:Protocol');
    }
}
