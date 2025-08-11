<?php

namespace App\Filament\Project\Resources\Specimens\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SpecimenForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('subject_event_id')
                    ->required()
                    ->numeric(),
                TextInput::make('specimen_type_id')
                    ->required()
                    ->numeric(),
                TextInput::make('site_id')
                    ->required()
                    ->numeric(),
                TextInput::make('status')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('aliquote')
                    ->required()
                    ->numeric(),
                TextInput::make('volume')
                    ->numeric()
                    ->default(null),
                TextInput::make('volumeUnit')
                    ->default(null),
                TextInput::make('thawcount')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('loggedBy')
                    ->required()
                    ->numeric(),
                DateTimePicker::make('loggedAt'),
                TextInput::make('loggedOutBy')
                    ->required()
                    ->numeric(),
                TextInput::make('usedBy')
                    ->required()
                    ->numeric(),
                DateTimePicker::make('usedAt'),
                TextInput::make('parentSpecimenID')
                    ->numeric()
                    ->default(null),
            ]);
    }
}
