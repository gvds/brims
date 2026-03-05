<?php

namespace App\Filament\Resources\Teams\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TeamInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('leader.fullname')
                    ->label('Team Leader'),
                TextEntry::make('description'),
            ])
            ->columns(3)
            ->extraAttributes(['class' => 'bg-white dark:bg-zinc-900 p-5 rounded-lg border border-zinc-200 dark:border-zinc-800']);
    }
}
