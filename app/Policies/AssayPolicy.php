<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Assay;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssayPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Assay');
    }

    public function view(AuthUser $authUser, Assay $assay): bool
    {
        return $authUser->can('View:Assay');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Assay');
    }

    public function update(AuthUser $authUser, Assay $assay): bool
    {
        return $authUser->can('Update:Assay');
    }

    public function delete(AuthUser $authUser, Assay $assay): bool
    {
        return $authUser->can('Delete:Assay');
    }

    public function reorder(AuthUser $authUser, Assay $assay): bool
    {
        return $authUser->can('Reorder:Assay');
    }

}