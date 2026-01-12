<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Site;
use Illuminate\Auth\Access\HandlesAuthorization;

class SitePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {

        return evaluate_permission($authUser, 'ViewAny:Site');
    }

    public function view(AuthUser $authUser, Site $site): bool
    {
        return evaluate_permission($authUser, 'View:Site');
    }

    public function create(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Create:Site');
    }

    public function update(AuthUser $authUser, Site $site): bool
    {
        return evaluate_permission($authUser, 'Update:Site');
    }

    public function delete(AuthUser $authUser, Site $site): bool
    {
        return evaluate_permission($authUser, 'Delete:Site');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'DeleteAny:Site');
    }

    public function reorder(AuthUser $authUser, Site $site): bool
    {
        return evaluate_permission($authUser, 'Reorder:Site');
    }
}
