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
            ->columns([
                TextColumn::make('id')
                    ->label('ID'),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('leader.fullname')
                    ->searchable(['firstname', 'lastname']),
                TextColumn::make('team.name')
                    ->searchable(),
            ])
            ->defaultSort('title')
            ->recordUrl(null)
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('Access')
                    // ->label('Administration')
                    ->icon('heroicon-o-eye')
                    ->button()
                    ->action(function (Project $record) {
                        session(['currentProject' => $record]);
                        // setPermissionsTeamId($record->id);
                        return to_route('filament.project.pages.dashboard', $parameters = ['tenant' => $record->id]);
                    })
                    ->extraAttributes(['class' => 'bg-sky-200 border border-sky-500 shadow-sm text-gray-900 py-0 leading-6 hover:invert [&_svg]:text-black']),
            ], position: RecordActionsPosition::BeforeCells);
    }
}
