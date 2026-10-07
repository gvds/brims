<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Site;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class SitePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {

        return evaluate_permission($authUser, 'View:Project');
    }

    public function view(AuthUser $authUser, Site $site): bool
    {
        return evaluate_permission($authUser, 'View:Project');
    }

    public function create(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Manage:Project');
    }

    public function update(AuthUser $authUser, Site $site): bool
    {
        return evaluate_permission($authUser, 'Manage:Project');
    }

    public function delete(AuthUser $authUser, Site $site): bool
    {
        return evaluate_permission($authUser, 'Delete:Project');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Delete:Project');
    }
}
