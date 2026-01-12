<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Publication;
use Illuminate\Auth\Access\HandlesAuthorization;

class PublicationPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'ViewAny:Publication');
    }

    public function view(AuthUser $authUser, Publication $publication): bool
    {
        return evaluate_permission($authUser, 'View:Publication');
    }

    public function create(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Create:Publication');
    }

    public function update(AuthUser $authUser, Publication $publication): bool
    {
        return evaluate_permission($authUser, 'Update:Publication');
    }

    public function delete(AuthUser $authUser, Publication $publication): bool
    {
        return evaluate_permission($authUser, 'Delete:Publication');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'DeleteAny:Publication');
    }

    public function reorder(AuthUser $authUser, Publication $publication): bool
    {
        return evaluate_permission($authUser, 'Reorder:Publication');
    }
}
