<?php

namespace App\Filament\Resources\Projects\Resources\Labware\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LabwareForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('barcodeFormat')
                    ->label('Barcode Format Regex')
                    ->hint('Use a regular expression to define the barcode format')
                    ->helperText('The Regex must begin with ^ and end with $. These will be automatically added if not present.')
                    ->required(),
            ])
            ->columns(1);
    }
}
