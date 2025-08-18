<?php

namespace App\Filament\Resources\Projects\Tables;

use App\Models\Project;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        session()->forget('currentProject');
        return $table
            // ->striped()
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->action(function (Project $record) {
                        session(['currentProject' => $record]);
                        return redirect()->route('filament.project.resources.subjects.index');
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
                // Action::make('access')
                //     ->icon('heroicon-o-key')
                //     ->button()
                //     ->extraAttributes(['class' => 'dark:text-gray-900 hover:invert'])
                //     ->action(function (Model $record) {
                //         session(['currentProject' => $record]);
                //         return redirect()->route('filament.project.resources.subjects.index');
                //     }),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
