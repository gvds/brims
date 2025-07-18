<?php

namespace App\Filament\Resources\Assays\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AssayForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(100),
                Select::make('assaydefinition_id')
                    ->relationship('assaydefinition', 'name')
                    ->required(),
                TextInput::make('technologyPlatform')
                    ->required()
                    ->maxLength(50),
                FileUpload::make('assayfile'),
                TextInput::make('uri'),
                TextInput::make('location'),
            ])
            ->columns(1)
            ->extraAttributes(['class' => 'w-1/2']);
    }
}
