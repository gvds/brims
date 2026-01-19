<?php

namespace App\Filament\Project\Resources\Specimens\Schemas;

use App\Enums\SpecimenStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

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
                    ->label('Subject Event'),
                Select::make('specimenType_id')
                    ->relationship(name: 'specimenType', titleAttribute: 'name')
                    ->required(),
                Select::make('site_id')
                    ->relationship(name: 'site', titleAttribute: 'name')
                    ->required(),
                Select::make('status')
                    ->options(SpecimenStatus::class)
                    ->required()
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
                    ->requiredIf(
                        fn(Get $get): bool => in_array($get('status'), [SpecimenStatus::Logged, SpecimenStatus::LoggedOut]),
                        true
                    ), 
                DatePicker::make('loggedAt'),
                Select::make('loggedOutBy_id')
                    ->label('Logged Out By')
                    ->relationship(name: 'loggedOutBy', titleAttribute: 'firstname')
                    ->requiredIf(
                        fn(Get $get): bool => in_array($get('status'), [SpecimenStatus::LoggedOut]),
                        true
                    ), 
                Select::make('usedBy_id')
                    ->relationship(name: 'usedBy', titleAttribute: 'firstname')
                    ->requiredIf(fn(Get $get): bool => in_array($get('status'), [SpecimenStatus::Used]), true), 
                DatePicker::make('usedAt'),
                Select::make('parentSpecimen_id')
                    ->relationship(name: 'parentSpecimen', titleAttribute: 'barcode')
                    ->default(null),
            ]);
    }
}
