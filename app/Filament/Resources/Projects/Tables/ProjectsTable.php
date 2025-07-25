<?php

namespace App\Filament\Resources\Projects\Tables;

use App\Models\Project;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                // ->extraAttributes(['class' => 'text-sky-800 dark:text-sky-400']),
                TextColumn::make('team.name')
                    ->searchable(),
                TextColumn::make('leader.fullname')
                    ->searchable(),
                TextColumn::make('submission_date')
                    ->date('Y-m-d')
                    ->sortable(),
                TextColumn::make('public_release_date')
                    ->date('Y-m-d')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('title')
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('Access')
                    ->icon('heroicon-o-chevron-double-right')
                    ->action(function (Project $project) {
                        session(['currentProject' => $project]);
                        redirect()->route('filament.project.pages.dashboard');
                    })
                    ->button()
                    ->extraAttributes(['class' => 'hover:invert']),
            ], position: RecordActionsPosition::BeforeColumns)
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
