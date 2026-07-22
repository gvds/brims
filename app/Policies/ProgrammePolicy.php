<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Programme;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProgrammePolicy
{

    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $authUser): bool
    {
        return evaluate_permission($authUser, 'View:Programme');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $authUser, Programme $programme): bool
    {
        return evaluate_permission($authUser, 'View:Programme');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $authUser): bool
    {
        return evaluate_permission($authUser, 'Manage:Programme');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $authUser, Programme $programme): bool
    {
        return evaluate_permission($authUser, 'Manage:Programme');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $authUser, Programme $programme): bool
    {
        return evaluate_permission($authUser, 'Delete:Programme');
    }
}
