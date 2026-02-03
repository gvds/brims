<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\SystemRoles;
use App\Enums\TeamRoles;
use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Studydesign;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudydesignPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return true;
    }

    public function view(AuthUser $authUser, Studydesign $studydesign): bool
    {
        return evaluate_permission($authUser, 'View:Studydesign');
    }

    public function create(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'Create:Studydesign');
    }

    public function update(AuthUser $authUser, Studydesign $studydesign): bool
    {
        return evaluate_permission($authUser, 'Update:Studydesign');
    }

    public function delete(AuthUser $authUser, Studydesign $studydesign): bool
    {
        return evaluate_permission($authUser, 'Delete:Studydesign');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return evaluate_permission($authUser, 'DeleteAny:Studydesign');
    }
}
