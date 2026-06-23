<?php

namespace App\Filament\App\Resources\Teams\Resources\Programmes\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProgrammeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->columnSpanFull()
                    ->placeholder('-'),
                TextEntry::make('funder')
                    ->placeholder('-'),
                TextEntry::make('grantNumber')
                    ->placeholder('-'),
                TextEntry::make('pi.fullname')
                    ->label('PI')
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                // TextEntry::make('created_at')
                //     ->dateTime()
                //     ->placeholder('-'),
                // TextEntry::make('updated_at')
                //     ->dateTime()
                //     ->placeholder('-'),
            ])
            ->extraAttributes(['class' => 'w-1/2']);
    }
}
