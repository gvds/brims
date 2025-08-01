<?php

namespace App\Filament\Resources\Teams\Resources\Studydesigns\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StudydesignForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('type')
                    ->required(),
                TextInput::make('type_term_accession_number')
                    ->requiredWith('type_term_reference')
                    ->default(null),
                TextInput::make('type_term_reference')
                    ->requiredWith('type_term_accession_number')
                    ->default(null),
            ])
            ->columns(1);
    }
}
