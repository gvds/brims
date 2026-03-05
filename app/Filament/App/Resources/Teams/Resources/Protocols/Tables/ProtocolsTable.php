<?php

namespace App\Filament\Resources\Teams\Resources\Protocols\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProtocolsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('user.fullname')
                    ->label('Creator')
                    ->searchable(),
                TextColumn::make('type')
                    ->searchable(),
                TextColumn::make('description')
                    ->searchable(),
                TextColumn::make('uri')
                    ->searchable(),
                TextColumn::make('version')
                    ->searchable(),
                TextColumn::make('parameters_names')
                    ->searchable(),
                TextColumn::make('components_names')
                    ->searchable(),
                TextColumn::make('components_type')
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
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
