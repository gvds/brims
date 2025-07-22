<?php

namespace App\Filament\Resources\Projects\Resources\Specimentypes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SpecimentypesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('project.title')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable(),
                IconColumn::make('primary')
                    ->boolean(),
                TextColumn::make('aliquots')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('pooled')
                    ->boolean(),
                TextColumn::make('defaultVolume')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('volumeUnit')
                    ->searchable(),
                IconColumn::make('store')
                    ->boolean(),
                TextColumn::make('sampleGroup')
                    ->searchable(),
                TextColumn::make('labware.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('storageDestination')
                    ->searchable(),
                TextColumn::make('storageSpecimenType')
                    ->searchable(),
                TextColumn::make('parentSpecimenType_id')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('active')
                    ->boolean(),
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
