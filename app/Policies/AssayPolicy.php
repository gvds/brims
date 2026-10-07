<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Assay;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class AssayPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'View:Assay');
    }

    public function view(AuthUser $authUser, Assay $assay): bool
    {
        return evaluate_permission($authUser, 'View:Assay');
    }

    public function create(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Manage:Assay');
    }

    public function update(AuthUser $authUser, Assay $assay): bool
    {
        return evaluate_permission($authUser, 'Manage:Assay');
    }

    public function delete(AuthUser $authUser, Assay $assay): bool
    {
        return evaluate_permission($authUser, 'Delete:Assay');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Delete:Assay');
    }
}
