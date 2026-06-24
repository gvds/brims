<?php

namespace App\Filament\Project\Resources\Projects\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProgrammesRelationManager extends RelationManager
{
    protected static string $relationship = 'programmes';

    #[\Override]
    public function isReadOnly(): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('funder')
                    ->searchable(),
                TextColumn::make('grantNumber')
                    ->searchable(),
                TextColumn::make('pi.fullname')
                    ->label('PI')
                    ->searchable(),
                TextColumn::make('team.name')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->attachAnother(false),
            ])
            ->recordActions([
                DetachAction::make(),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
