<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ManifestStatus: int implements HasLabel
{
    case Open = 0;
    case Shipped = 1;
    case Received = 2;

    public function getLabel(): ?string
    {
        return $this->name;
    }
}
