<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Study;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudyPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Study');
    }

    public function view(AuthUser $authUser, Study $study): bool
    {
        return $authUser->can('View:Study');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Study');
    }

    public function update(AuthUser $authUser, Study $study): bool
    {
        return $authUser->can('Update:Study');
    }

    public function delete(AuthUser $authUser, Study $study): bool
    {
        return $authUser->can('Delete:Study');
    }

    public function reorder(AuthUser $authUser, Study $study): bool
    {
        return $authUser->can('Reorder:Study');
    }

}