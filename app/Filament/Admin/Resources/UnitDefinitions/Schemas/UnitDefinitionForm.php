<?php

namespace App\Filament\Admin\Resources\UnitDefinitions\Schemas;

use App\Enums\StorageType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UnitDefinitionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(100),
                Select::make('storageType')
                    ->options(StorageType::class)
                    ->in(StorageType::cases())
                    ->required(),
                Select::make('orientation')
                    ->options([
                        'Chest' => 'Chest',
                        'Upright' => 'Upright',
                    ])
                    ->in(['Chest', 'Upright'])
                    ->required(),
                Select::make('sectionLayout')
                    ->options([
                        'Horizontal' => 'Horizontal',
                        'Vertical' => 'Vertical',
                    ])
                    ->in(['Horizontal', 'Vertical'])
                    ->required(),
                Select::make('rackOrder')
                    ->options([
                        'Row-wise' => 'Row-wise',
                        'Column-wise' => 'Column-wise',
                    ])
                    ->in(['Row-wise', 'Column-wise'])
                    ->required(),
                Select::make('boxDesignation')
                    ->options([
                        'Alpha' => 'Alpha',
                        'Numeric' => 'Numeric',
                    ])
                    ->in(['Alpha', 'Numeric'])
                    ->required(),
            ])
            ->extraAttributes(['class' => 'w-1/3']);
    }
}
