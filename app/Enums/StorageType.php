<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum StorageType: string implements HasColor, HasLabel
{
    case Minus80 = 'Minus 80';
    case LiquidNitrogen = 'Liquid Nitrogen';
    case Biorepository = 'Biorepository';

    public function getLabel(): string
    {
        return $this->value;
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Minus80 => Color::Purple,
            self::LiquidNitrogen => Color::Sky,
            self::Biorepository => Color::Green,
        };
    }
}
