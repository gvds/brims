<?php

namespace App\Filament\Admin\Resources\PhysicalUnits\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PhysicalUnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Select::make('unitDefinition_id')
                    ->relationship('unitDefinition', 'name')
                    ->required(),
                TextInput::make('serial')
                    ->default(null),
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Toggle::make('available')
                    ->required(),
            ]);
    }
}
