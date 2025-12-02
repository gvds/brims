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
        return $authUser->can('ViewAny:Specimen');
    }

    public function view(AuthUser $authUser, Specimen $specimen): bool
    {
        return $authUser->can('View:Specimen');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Specimen');
    }

    public function update(AuthUser $authUser, Specimen $specimen): bool
    {
        return $authUser->can('Update:Specimen');
    }

    public function delete(AuthUser $authUser, Specimen $specimen): bool
    {
        return $authUser->can('Delete:Specimen');
    }

    public function reorder(AuthUser $authUser, Specimen $specimen): bool
    {
        return $authUser->can('Reorder:Specimen');
    }
}
