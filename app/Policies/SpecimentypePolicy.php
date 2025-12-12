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
        return evaluate_permission($authUser, 'ViewAny:Specimentype');
    }

    public function view(AuthUser $authUser, Specimentype $specimentype): bool
    {
        return evaluate_permission($authUser, 'View:Specimentype');
    }

    public function create(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Create:Specimentype');
    }

    public function update(AuthUser $authUser, Specimentype $specimentype): bool
    {
        return evaluate_permission($authUser, 'Update:Specimentype');
    }

    public function delete(AuthUser $authUser, Specimentype $specimentype): bool
    {
        return evaluate_permission($authUser, 'Delete:Specimentype');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'DeleteAny:Specimentype');
    }

    public function reorder(AuthUser $authUser, Specimentype $specimentype): bool
    {
        return evaluate_permission($authUser, 'Reorder:Specimentype');
    }
}
