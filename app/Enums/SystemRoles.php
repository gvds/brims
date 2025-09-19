<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SystemRoles: int implements HasLabel
{
    case SuperAdmin = 0;
    case User = 1;
    case SysAdmin = 2;
    case CryoAdmin = 3;

    public function getLabel(): ?string
    {
        return $this->name;
    }
}
