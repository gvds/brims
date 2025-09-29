<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Specimentype;
use Illuminate\Auth\Access\HandlesAuthorization;

class SpecimentypePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Specimentype');
    }

    public function view(AuthUser $authUser, Specimentype $specimentype): bool
    {
        return $authUser->can('View:Specimentype');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Specimentype');
    }

    public function update(AuthUser $authUser, Specimentype $specimentype): bool
    {
        return $authUser->can('Update:Specimentype');
    }

    public function delete(AuthUser $authUser, Specimentype $specimentype): bool
    {
        return $authUser->can('Delete:Specimentype');
    }

    public function reorder(AuthUser $authUser, Specimentype $specimentype): bool
    {
        return $authUser->can('Reorder:Specimentype');
    }

}