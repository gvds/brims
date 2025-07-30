<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SubjectStatus: int implements HasLabel
{
    case Pending = 0;
    case Queued = 1;
    case Generated = 2;

    public function getLabel(): ?string
    {
        return $this->name;
    }
}
