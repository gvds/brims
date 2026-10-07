<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Publication;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class PublicationPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'View:Publication');
    }

    public function view(AuthUser $authUser, Publication $publication): bool
    {
        return evaluate_permission($authUser, 'View:Publication');
    }

    public function create(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Manage:Publication');
    }

    public function update(AuthUser $authUser, Publication $publication): bool
    {
        return evaluate_permission($authUser, 'Manage:Publication');
    }

    public function delete(AuthUser $authUser, Publication $publication): bool
    {
        return evaluate_permission($authUser, 'Delete:Publication');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Delete:Publication');
    }
}
