<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SubjectStatus: int implements HasLabel
{
    case Generated = 0;
    case Enrolled = 1;
    case Dropped = 2;

    public function getLabel(): ?string
    {
        return $this->name;
    }
}
