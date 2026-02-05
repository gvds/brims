<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ManifestStatus: int implements HasColor, HasLabel
{
    case Open = 0;
    case Shipped = 1;
    case Received = 2;

    public function getLabel(): ?string
    {
        return $this->name;
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Open => 'warning',
            self::Shipped => 'primary',
            self::Received => 'success',
        };
    }
}
