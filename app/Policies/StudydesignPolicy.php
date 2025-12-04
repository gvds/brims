<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Studydesign;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudydesignPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Studydesign');
    }

    public function view(AuthUser $authUser, Studydesign $studydesign): bool
    {
        return $authUser->can('View:Studydesign');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Studydesign');
    }

    public function update(AuthUser $authUser, Studydesign $studydesign): bool
    {
        return $authUser->can('Update:Studydesign');
    }

    public function delete(AuthUser $authUser, Studydesign $studydesign): bool
    {
        return $authUser->can('Delete:Studydesign');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Studydesign');
    }
    public function reorder(AuthUser $authUser, Studydesign $studydesign): bool
    {
        return $authUser->can('Reorder:Studydesign');
    }
}
