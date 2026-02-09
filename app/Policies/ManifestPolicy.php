<?php

namespace App\Policies;

use App\Models\Manifest;
use App\Models\User;

class ManifestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $authUser): bool
    {
        return evaluate_permission($authUser, 'ViewAny:Manifest');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $authUser, Manifest $manifest): bool
    {
        return evaluate_permission($authUser, 'View:Manifest');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $authUser): bool
    {
        return evaluate_permission($authUser, 'Create:Manifest');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $authUser, Manifest $manifest): bool
    {
        return evaluate_permission($authUser, 'Update:Manifest');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $authUser, Manifest $manifest): bool
    {
        return evaluate_permission($authUser, 'Delete:Manifest');
    }
}
