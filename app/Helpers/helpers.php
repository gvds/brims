<?php

use App\Enums\SystemRoles;
use Filament\Facades\Filament;

function evaluate_permission($authUser, string $permission): bool
{
    $conditions = [
        $authUser->system_role === SystemRoles::SysAdmin,
        Filament::getCurrentPanel()->getId() === 'app' && $authUser->is_team_admin,
        $authUser->is_team_admin && session('currentProject')?->team_id === $authUser->team_id,
        $authUser->can($permission),
    ];

    return in_array(true, $conditions, true);
}
