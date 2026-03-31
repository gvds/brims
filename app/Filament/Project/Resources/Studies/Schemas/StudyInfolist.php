<?php

namespace App\Filament\Project\Resources\Studies\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class StudyInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema([
                        TextEntry::make('title'),
                        TextEntry::make('identifier'),
                    ]),
                TextEntry::make('description'),
                Grid::make(3)
                    ->schema([
                        TextEntry::make('submission_date')
                            ->date('Y-m-d'),
                        TextEntry::make('public_release_date')
                            ->date('Y-m-d'),
                        IconEntry::make('locked')
                            ->label('Specimens Lock')
                            ->trueIcon('heroicon-o-lock-closed')
                            ->falseIcon('heroicon-o-lock-open')
                            ->trueColor('warning')
                            ->falseColor('success')
                            ->helperText('When locked, specimens cannot be added or removed.'),
                    ]),
                // TextEntry::make('studyfilename'),
            ])
            ->columns(['sm' => 2, 'xl' => 2])
            ->extraAttributes(['class' => 'border border-gray-200 rounded-lg py-4 px-5 bg-gray-50 dark:bg-zinc-900 dark:border-zinc-800']);
    }
}
