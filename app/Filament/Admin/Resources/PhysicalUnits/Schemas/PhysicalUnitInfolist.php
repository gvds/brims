<?php

namespace App\Filament\Admin\Resources\PhysicalUnits\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PhysicalUnitInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('unitDefinition.name')
                    ->label('Unit Definition'),
                TextEntry::make('unitDefinition.storageType')
                    ->label('Storage Type'),
                TextEntry::make('serial')
                    ->label('Serial Number'),
                TextEntry::make('administrator.fullname')
                    ->label('Administrator'),
                IconEntry::make('available')
                    ->boolean(),
            ])
            ->columns(3)
            ->extraAttributes(['class' => 'bg-white dark:bg-zinc-900  border border-gray-200 dark:border-zinc-800 shadow-sm rounded-xl p-6']);
    }
}
