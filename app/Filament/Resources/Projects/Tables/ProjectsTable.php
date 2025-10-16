<?php

namespace App\Filament\Resources\Projects\Tables;

use App\Models\Project;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // ->striped()
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->action(function (Project $record) {
                        if (auth()->user()->can('view', $record)) {
                            session(['currentProject' => $record]);
                            return to_route('filament.project.pages.dashboard', $parameters = ['tenant' => $record->id]);
                        }
                    })
                    ->extraAttributes(['class' => 'text-sky-800 dark:text-sky-500 hover:invert']),
                TextColumn::make('team.name')
                    ->searchable(),
                TextColumn::make('leader.fullname')
                    ->searchable(['firstname', 'lastname']),
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
            ->recordUrl(
                null
            )
            ->recordActions([
                ViewAction::make()
                    ->label('Administration')
                    ->icon('heroicon-o-eye')
                    ->button()
                    ->extraAttributes(['class' => 'bg-sky-200 dark:text-gray-900 py-1 hover:invert']),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
