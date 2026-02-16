<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\StudyDesign;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudyDesignPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return true;
    }

    public function view(AuthUser $authUser, StudyDesign $studydesign): bool
    {
        return evaluate_permission($authUser, 'View:StudyDesign');
    }

    public function create(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Create:StudyDesign');
    }

    public function update(AuthUser $authUser, StudyDesign $studydesign): bool
    {
        return evaluate_permission($authUser, 'Update:StudyDesign');
    }

    public function delete(AuthUser $authUser, StudyDesign $studydesign): bool
    {
        return evaluate_permission($authUser, 'Delete:StudyDesign');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'DeleteAny:StudyDesign');
    }
}
