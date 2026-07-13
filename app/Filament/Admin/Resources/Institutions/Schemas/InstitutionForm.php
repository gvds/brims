<?php

namespace App\Filament\Admin\Resources\Institutions\Schemas;

use CountryEnums\Country;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InstitutionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Select::make('country')
                    ->options(Country::getOptions())
                    ->in(Country::cases())
                    ->required()
            ]);
    }
}
