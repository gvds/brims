<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum EventStatus: int implements HasLabel, HasColor
{
    case Pending = 0;
    case Primed = 1;
    case Scheduled = 2;
    case Logged = 3;
    case LoggedLate = 4;
    case Missed = 5;
    case Cancelled = 6;

    public function getLabel(): ?string
    {
        return $this->name;
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Pending => 'gray',
            self::Primed => 'info',
            self::Scheduled => 'warning',
            self::Logged => 'success',
            self::LoggedLate => 'success',
            self::Missed => 'danger',
            self::Cancelled => 'danger',
        };
    }
}
