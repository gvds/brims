<?php

namespace App\Filament\Project\Resources\Specimens\Schemas;

use App\Enums\SpecimenStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
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
                TextInput::make('specimenType_id')
                    ->required()
                    ->numeric(),
                TextInput::make('site_id')
                    ->required()
                    ->numeric(),
                TextInput::make('status')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('aliquot')
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
                    ->rules([
                        'requiredIf' => fn(Get $get) => in_array($get('status'), [SpecimenStatus::Logged, SpecimenStatus::LoggedOut])
                    ])
                    ->numeric(),
                DateTimePicker::make('loggedAt'),
                TextInput::make('loggedOutBy')
                    ->rules([
                        'requiredIf' => fn(Get $get) => in_array($get('status'), [SpecimenStatus::LoggedOut])
                    ])
                    ->numeric(),
                TextInput::make('usedBy')
                    ->rules([
                        'requiredIf' => fn(Get $get) => in_array($get('status'), [SpecimenStatus::Used])
                    ])
                    ->numeric(),
                DateTimePicker::make('usedAt'),
                TextInput::make('parentSpecimenID')
                    ->numeric()
                    ->default(null),
            ]);
    }
}
