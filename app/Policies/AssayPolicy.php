<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\StudyRoles;
use App\Enums\TeamRoles;
use App\Models\Assay;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Support\Facades\Route;

class AssayPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        if (Filament::getCurrentPanel()->getId() === 'app') {
            return true;
        }
        return Route::current()->getName() === 'filament.app.resources.studies.view';
    }

    public function view(AuthUser $authUser, Assay $assay): bool
    {
        return $authUser->team_role === TeamRoles::Admin ||
            $assay->study->members()->where('user_id', $authUser->id)->exists();
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->team_role === TeamRoles::Admin;
    }

    public function update(AuthUser $authUser, Assay $assay): bool
    {
        return $authUser->team_role === TeamRoles::Admin ||
            $assay->study->members()->where('user_id', $authUser->id)->where('role', StudyRoles::Lead)->exists();
    }

    public function delete(AuthUser $authUser, Assay $assay): bool
    {
        return $authUser->team_role === TeamRoles::Admin ||
            $assay->study->members()->where('user_id', $authUser->id)->where('role', StudyRoles::Lead)->exists();
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return false;
    }
}
