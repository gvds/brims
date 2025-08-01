<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PublicationStatus: string implements HasLabel, HasColor

{
    case Draft = 'draft';
    case Submitted = 'submitted';
    case Published = 'published';

    public function getLabel(): string
    {
        return $this->name;
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Submitted => 'info',
            self::Published => 'success',
        };
    }

    // publication_status_term_accession_number
    // publication_status_term_reference
}
