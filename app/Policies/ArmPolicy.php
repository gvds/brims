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
        return $authUser->can('ViewAny:Arm');
    }

    public function view(AuthUser $authUser, Arm $arm): bool
    {
        return $authUser->can('View:Arm');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Arm');
    }

    public function update(AuthUser $authUser, Arm $arm): bool
    {
        return $authUser->can('Update:Arm');
    }

    public function delete(AuthUser $authUser, Arm $arm): bool
    {
        return $authUser->can('Delete:Arm');
    }

    public function reorder(AuthUser $authUser, Arm $arm): bool
    {
        return $authUser->can('Reorder:Arm');
    }

}