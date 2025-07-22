<?php

namespace App\Filament\Resources\Projects\Resources\Specimentypes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SpecimentypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('project_id')
                    ->relationship('project', 'title')
                    ->required(),
                TextInput::make('name')
                    ->default(null),
                Toggle::make('primary')
                    ->required(),
                TextInput::make('aliquots')
                    ->required()
                    ->numeric(),
                Toggle::make('pooled')
                    ->required(),
                TextInput::make('defaultVolume')
                    ->numeric()
                    ->default(null),
                TextInput::make('volumeUnit')
                    ->default(null),
                Toggle::make('store')
                    ->required(),
                Textarea::make('transferDestinations')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('sampleGroup')
                    ->default(null),
                Select::make('labware_id')
                    ->relationship('labware', 'name')
                    ->required(),
                TextInput::make('storageDestination')
                    ->default(null),
                TextInput::make('storageSpecimenType')
                    ->default(null),
                TextInput::make('parentSpecimenType_id')
                    ->numeric()
                    ->default(null),
                Toggle::make('active')
                    ->required(),
            ]);
    }
}
