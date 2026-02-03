<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Specimen;
use Illuminate\Auth\Access\HandlesAuthorization;

class SpecimenPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'ViewAny:Specimen');
    }

    public function view(AuthUser $authUser, Specimen|string $specimen): bool
    {
        return evaluate_permission($authUser, 'View:Specimen');
    }

    public function create(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Create:Specimen');
    }

    public function update(AuthUser $authUser, Specimen|string $specimen): bool
    {
        return evaluate_permission($authUser, 'Update:Specimen');
    }

    public function delete(AuthUser $authUser, Specimen|string $specimen): bool
    {
        return evaluate_permission($authUser, 'Delete:Specimen');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'DeleteAny:Specimen');
    }
}
