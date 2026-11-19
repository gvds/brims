<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum StudyRoles: string implements HasLabel
{
    case Lead = 'lead';
    case Member = 'member';

    public function getLabel(): ?string
    {
        return $this->name;
    }
}
