<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum SubjectStatus: int implements HasLabel, HasColor
{
    case Generated = 0;
    case Enrolled = 1;
    case Dropped = 2;

    public function getLabel(): ?string
    {
        return $this->name;
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Generated => 'info',
            self::Enrolled => 'success',
            self::Dropped => 'danger',
        };
    }
}
