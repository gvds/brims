<?php

namespace App\Filament\Project\Resources\Projects\Resources\Specimentypes\Tables;

use App\Models\Specimentype;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class SpecimentypesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable(isIndividual: true, isGlobal: false),
                IconColumn::make('primary')
                    ->boolean(),
                TextColumn::make('aliquots')
                    ->numeric(),
                IconColumn::make('pooled')
                    ->boolean(),
                TextColumn::make('defaultVolume')
                    ->formatStateUsing(fn(Specimentype $record): string => $record->defaultVolume . ' ' . $record->volumeUnit),
                TextColumn::make('specimenGroup')
                    ->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('labware.name')
                    ->searchable(isIndividual: true, isGlobal: false),
                IconColumn::make('store')
                    ->boolean(),
                TextColumn::make('storageDestination')
                    ->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('storageSpecimenType')
                    ->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('parentSpecimenType.name')
                    ->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('transferDestinations')
                    ->formatStateUsing(fn($state) => collect($state)->implode(', '))
                    ->listWithLineBreaks()
                    ->searchable(isIndividual: true, isGlobal: false)
                    ->size('xs'),
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
                TernaryFilter::make('primary')
                    ->label('Primary'),
            ])
            ->deferFilters(false)
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
