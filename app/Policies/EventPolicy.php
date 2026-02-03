<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Event;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'ViewAny:Event');
    }

    public function view(AuthUser $authUser, Event $event): bool
    {
        return evaluate_permission($authUser, 'View:Event');
    }

    public function create(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Create:Event');
    }

    public function update(AuthUser $authUser, Event $event): bool
    {
        return evaluate_permission($authUser, 'Update:Event');
    }

    public function delete(AuthUser $authUser, Event $event): bool
    {
        return evaluate_permission($authUser, 'Delete:Event');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'DeleteAny:Event');
    }
}
