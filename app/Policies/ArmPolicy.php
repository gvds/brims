<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Arm;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class ArmPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {

        return evaluate_permission($authUser, 'View:Project');
    }

    public function view(AuthUser $authUser, Arm $arm): bool
    {
        return evaluate_permission($authUser, 'View:Project');
    }

    public function create(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Manage:Project');
    }

    public function update(AuthUser $authUser, Arm $arm): bool
    {
        return evaluate_permission($authUser, 'Manage:Project');
    }

    public function delete(AuthUser $authUser, Arm $arm): bool
    {
        return evaluate_permission($authUser, 'Delete:Project');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Delete:Project');
    }
}
