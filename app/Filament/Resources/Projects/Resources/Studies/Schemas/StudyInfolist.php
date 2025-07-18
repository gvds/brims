<?php

namespace App\Filament\Resources\Projects\Resources\Studies\Schemas;

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
                    ])
                    ->columnSpan(2),
                Grid::make(2)
                    ->schema([
                        TextEntry::make('submission_date')
                            ->date('Y-m-d'),
                        TextEntry::make('public_release_date')
                            ->date('Y-m-d'),
                    ]),
                TextEntry::make('studyfile'),
                // TextEntry::make('studyfilename'),
            ])
            ->columns(2)
            ->extraAttributes(['class' => 'border border-gray-200 rounded-lg py-4 px-5 bg-gray-50 dark:bg-zinc-900 dark:border-zinc-800']);
    }
}
