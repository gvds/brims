<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class ProjectInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make([
                    'default' => 1,
                    'sm' => 3,
                ])
                    ->schema([
                        TextEntry::make('title')
                            ->size('md')
                            ->weight('bold')
                            ->color('primary'),
                        TextEntry::make('leader.fullname')
                            ->label('Project Leader'),
                        TextEntry::make('team.name')
                            ->label('Project Team'),
                        TextEntry::make('identifier'),
                        TextEntry::make('submission_date')
                            ->date('Y-m-d'),
                        TextEntry::make('public_release_date')
                            ->date('Y-m-d'),
                    ])
                    ->extraAttributes(['class' => 'min-w-full']),
                TextEntry::make('description')
                    ->wrap(true)
                    ->limit(500),
            ])
            ->extraAttributes(['class' => 'border border-gray-200 rounded-lg py-4 px-5 bg-gray-50 dark:bg-zinc-900 dark:border-zinc-800']);
    }
}
