<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Event;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class EventPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'View:Event');
    }

    public function view(AuthUser $authUser, Event $event): bool
    {
        return evaluate_permission($authUser, 'View:Event');
    }

    public function create(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Manage:Event');
    }

    public function update(AuthUser $authUser, Event $event): bool
    {
        return evaluate_permission($authUser, 'Manage:Event');
    }

    public function delete(AuthUser $authUser, Event $event): bool
    {
        return evaluate_permission($authUser, 'Delete:Event');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Delete:Event');
    }
}
