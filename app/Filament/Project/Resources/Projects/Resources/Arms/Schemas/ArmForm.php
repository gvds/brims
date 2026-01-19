<?php

namespace App\Filament\Project\Resources\Projects\Resources\Arms\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ArmForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextInput::make('name')
                //     ->default(null),
                // Toggle::make('manual_enrol')
                //     ->required()
                //     ->inline(false)
                //     ->default(false),
                // TextInput::make('arm_num')
                //     ->integer()
                //     ->default(null)
                //     ->minValue(1),
                // CheckboxList::make('switcharms')
                //     ->relationship(titleAttribute: 'name')
                // TextInput::make('switcharms')
                //     ->default(null),
            ]);
    }
}
