<?php

namespace App\Filament\Admin\Resources\UnitDefinitions\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class UnitDefinitionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(1)
                    ->schema([
                        TextEntry::make('storageType')
                            ->inlineLabel(),
                        TextEntry::make('orientation')
                            ->inlineLabel(),
                        TextEntry::make('sectionLayout')
                            ->inlineLabel(),
                        TextEntry::make('boxDesignation')
                            ->inlineLabel(),
                        TextEntry::make('rackOrder')
                            ->inlineLabel(),
                    ])
                    ->extraAttributes(['class' => 'max-w-max font-semibold']),

                RepeatableEntry::make('sections')
                    ->table([
                        TableColumn::make('#'),
                        TableColumn::make('Rows'),
                        TableColumn::make('Columns'),
                        TableColumn::make('Boxes'),
                        TableColumn::make('Positions'),
                    ])
                    ->schema([
                        TextEntry::make('section_number'),
                        TextEntry::make('rows'),
                        TextEntry::make('columns'),
                        TextEntry::make('boxes'),
                        TextEntry::make('positions'),
                    ]),
            ])
            ->columns([1, '2xl' => 2])
            ->extraAttributes(['class' => 'border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 rounded-lg p-6 shadow-sm']);
    }
}
