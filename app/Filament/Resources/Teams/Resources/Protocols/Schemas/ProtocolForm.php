<?php

namespace App\Filament\Resources\Teams\Resources\Protocols\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProtocolForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('type')
                    ->required(),
                TextInput::make('type_term_accession_number')
                    ->requiredWith('type_term_reference'),
                TextInput::make('type_term_reference')
                    ->requiredWith('type_term_accession_number'),
                TextInput::make('description')
                    ->required(),
                TextInput::make('uri')
                    ->required(),
                TextInput::make('version')
                    ->required(),
                TextInput::make('parameters_names')
                    ->required(),
                TextInput::make('parameters_term_accession_number')
                    ->required(),
                TextInput::make('parameters_term_reference')
                    ->required(),
                TextInput::make('components_names')
                    ->required(),
                TextInput::make('components_type')
                    ->required(),
                TextInput::make('components_type_term_accession_number')
                    ->required(),
                TextInput::make('components_type_term_reference')
                    ->required(),
            ]);
    }
}
