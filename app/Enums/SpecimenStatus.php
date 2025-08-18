<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum SpecimenStatus: int implements HasLabel, HasColor
{
    case Unassigned = 0;
    case Registered = 1;
    case Logged = 2;
    case InStorage = 3;
    case PreTransfer = 4;
    case Used = 5;
    case Reassigned = 6;
    case Transferred = 7;
    case Lost = 8;
    case LoggedOut = 9;
    case Received = 10;

    public function getLabel(): ?string
    {
        return $this->name;
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Logged => 'primary',
            self::Reassigned => 'primary',
            self::InStorage => 'success',
            self::Received => 'success',
            self::LoggedOut => 'warning',
            self::PreTransfer => 'info',
            self::Transferred => 'info',
            self::Lost => 'danger',
            self::Used => 'danger',
            default => 'gray',
        };
    }
}
