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
                    ->unique(ignoreRecord: true)
                    ->maxLength(30)
                    ->columnSpanFull(),
                Select::make('paper-size')
                    ->required()
                    ->options([
                        'A4' => 'A4',
                        'letter' => 'Letter',
                    ])
                    ->in(['A4', 'letter']),
                Select::make('metric')
                    ->required()
                    ->options([
                        'mm' => 'mm',
                        'in' => 'in',
                    ])
                    ->in(['mm', 'in']),
                TextInput::make('marginLeft')
                    ->required()
                    ->numeric()
                    ->rules(['decimal:0,3', 'max:999.999', 'min:0']),
                TextInput::make('marginTop')
                    ->required()
                    ->numeric()
                    ->rules(['decimal:0,3', 'max:999.999', 'min:0']),
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
                    ->numeric()
                    ->rules(['decimal:0,3', 'max:999.999', 'min:0']),
                TextInput::make('SpaceY')
                    ->label('Space Y')
                    ->required()
                    ->numeric()
                    ->rules(['decimal:0,3', 'max:999.999', 'min:0']),
                TextInput::make('width')
                    ->required()
                    ->numeric()
                    ->rules(['decimal:0,3', 'max:999.999', 'min:0']),
                TextInput::make('height')
                    ->required()
                    ->numeric()
                    ->rules(['decimal:0,3', 'max:999.999', 'min:0']),
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
