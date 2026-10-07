<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\StudyDesign;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class StudyDesignPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'View:StudyDesign');
    }

    public function view(AuthUser $authUser, StudyDesign $studydesign): bool
    {
        return evaluate_permission($authUser, 'View:StudyDesign');
    }

    public function create(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Manage:StudyDesign');
    }

    public function update(AuthUser $authUser, StudyDesign $studydesign): bool
    {
        return evaluate_permission($authUser, 'Manage:StudyDesign');
    }

    public function delete(AuthUser $authUser, StudyDesign $studydesign): bool
    {
        return evaluate_permission($authUser, 'Delete:StudyDesign');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Delete:StudyDesign');
    }
}
