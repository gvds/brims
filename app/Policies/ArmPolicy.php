<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Arm;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArmPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {

        return evaluate_permission($authUser, 'ViewAny:Arm');
    }

    public function view(AuthUser $authUser, Arm $arm): bool
    {
        return evaluate_permission($authUser, 'View:Arm');
    }

    public function create(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Create:Arm');
    }

    public function update(AuthUser $authUser, Arm $arm): bool
    {
        return evaluate_permission($authUser, 'Update:Arm');
    }

    public function delete(AuthUser $authUser, Arm $arm): bool
    {
        return evaluate_permission($authUser, 'Delete:Arm');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'DeleteAny:Arm');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Reorder:Arm');
    }
}
