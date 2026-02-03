<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Study;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class StudyPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'ViewAny:Study');
    }

    public function view(AuthUser $authUser, Study $study): bool
    {
        return evaluate_permission($authUser, 'View:Study');
    }

    public function create(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Create:Study');
    }

    public function update(AuthUser $authUser, Study $study): bool
    {
        return evaluate_permission($authUser, 'Update:Study');
    }

    public function delete(AuthUser $authUser, Study $study): bool
    {
        return evaluate_permission($authUser, 'Delete:Study');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'DeleteAny:Study');
    }
}
