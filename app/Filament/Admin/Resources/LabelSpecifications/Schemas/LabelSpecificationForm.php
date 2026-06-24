<?php

namespace App\Filament\Admin\Resources\LabelSpecifications\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LabelSpecificationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('format')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('paper-size')
                    ->required(),
                Select::make('metric')
                    ->required()
                    ->options([
                        'mm' => 'mm',
                        'in' => 'in',
                    ]),
                TextInput::make('marginLeft')
                    ->required()
                    ->numeric(),
                TextInput::make('marginTop')
                    ->required()
                    ->numeric(),
                TextInput::make('NX')
                    ->label('Count X')
                    ->required()
                    ->numeric()
                    ->rules(['between:1, 30']),
                TextInput::make('NY')
                    ->label('Count Y')
                    ->required()
                    ->numeric()
                    ->rules(['between:1, 30']),
                TextInput::make('SpaceX')
                    ->label('Space X')
                    ->required()
                    ->numeric(),
                TextInput::make('SpaceY')
                    ->label('Space Y')
                    ->required()
                    ->numeric(),
                TextInput::make('width')
                    ->required()
                    ->numeric(),
                TextInput::make('height')
                    ->required()
                    ->numeric(),
                TextInput::make('font-size')
                    ->required()
                    ->numeric()
                    ->rules(['between:7, 13']),
                TextInput::make('padding')
                    ->numeric()
                    ->rules(['between:0, 10'])
                    ->default(null),
            ])
            ->columns(2)
            ->extraAttributes(["class" => "max-w-1/3"]);
    }
}
