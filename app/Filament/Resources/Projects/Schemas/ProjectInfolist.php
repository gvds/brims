<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProjectInfolist
{


    public static function configure(Schema $schema): Schema
    {
        if (session('currentProject')?->id != $schema->model->id) {
            session(['currentProject' => $schema->model]);
            Notification::make('projectselection')
                ->title('Project Selected')
                ->body(Str::markdown("The current project has changed to <br> **" . $schema->model->title . "**"))
                ->status('success')
                ->color('info')
                ->send();
        }

        return $schema
            ->components([
                Grid::make([
                    'default' => 3,
                ])
                    ->schema([
                        TextEntry::make('title'),
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
                TextEntry::make('description'),
            ])
            ->extraAttributes(['class' => 'border border-gray-200 rounded-lg py-4 px-5 bg-gray-50 dark:bg-zinc-900 dark:border-zinc-800']);
    }
}
