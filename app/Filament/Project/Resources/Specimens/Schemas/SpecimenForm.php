<?php

namespace App\Filament\Project\Resources\Specimens\Schemas;

use App\Enums\SpecimenStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Factories\Relationship;

class SpecimenForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('barcode')
                    ->required()
                    ->unique(table: 'specimens', column: 'barcode', ignoreRecord: true)
                    ->maxLength(20),
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
                Select::make('loggedBy_id')
                    ->relationship(name: 'loggedBy', titleAttribute: 'firstname')
                    ->rules([
                        'requiredIf' => fn(Get $get): bool => in_array($get('status'), [SpecimenStatus::Logged, SpecimenStatus::LoggedOut])
                    ]),
                DatePicker::make('loggedAt'),
                Select::make('loggedOutBy')
                    ->relationship(name: 'loggedBy', titleAttribute: 'firstname')
                    ->rules([
                        'requiredIf' => fn(Get $get): bool => in_array($get('status'), [SpecimenStatus::LoggedOut])
                    ]),
                Select::make('usedBy')
                    ->relationship(name: 'loggedBy', titleAttribute: 'firstname')
                    ->rules([
                        'requiredIf' => fn(Get $get): bool => in_array($get('status'), [SpecimenStatus::Used])
                    ]),
                DatePicker::make('usedAt'),
                Select::make('parentSpecimen_id')
                    ->relationship(name: 'parentSpecimen', titleAttribute: 'barcode')
                    ->default(null),
            ]);
    }
}
