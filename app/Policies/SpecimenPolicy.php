<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Specimen;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class SpecimenPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'View:Specimen');
    }

    public function view(AuthUser $authUser, Specimen|string $specimen): bool
    {
        return evaluate_permission($authUser, 'View:Specimen');
    }

    public function create(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Manage:Specimen');
    }

    public function update(AuthUser $authUser, Specimen|string $specimen): bool
    {
        return evaluate_permission($authUser, 'Manage:Specimen');
    }

    public function delete(AuthUser $authUser, Specimen|string $specimen): bool
    {
        return evaluate_permission($authUser, 'Delete:Specimen');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Delete:Specimen');
    }
}
