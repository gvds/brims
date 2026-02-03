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
        return evaluate_permission($authUser, 'ViewAny:Labware');
    }

    public function view(AuthUser $authUser, Labware $labware): bool
    {
        return evaluate_permission($authUser, 'View:Labware');
    }

    public function create(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Create:Labware');
    }

    public function update(AuthUser $authUser, Labware $labware): bool
    {

        return evaluate_permission($authUser, 'Update:Labware');
    }

    public function delete(AuthUser $authUser, Labware $labware): bool
    {
        return evaluate_permission($authUser, 'Delete:Labware');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'DeleteAny:Labware');
    }
}
