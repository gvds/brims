<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Labware;
use Illuminate\Auth\Access\HandlesAuthorization;

class LabwarePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Labware');
    }

    public function view(AuthUser $authUser, Labware $labware): bool
    {
        return $authUser->can('View:Labware');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Labware');
    }

    public function update(AuthUser $authUser, Labware $labware): bool
    {
        return $authUser->can('Update:Labware');
    }

    public function delete(AuthUser $authUser, Labware $labware): bool
    {
        return $authUser->can('Delete:Labware');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Labware');
    }

    public function reorder(AuthUser $authUser, Labware $labware): bool
    {
        return $authUser->can('Reorder:Labware');
    }
}
