<?php

namespace App\Filament\Admin\Resources\UnitDefinitions\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UnitDefinitionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('orientation')
                    ->searchable(),
                TextColumn::make('sectionLayout')
                    ->searchable(),
                TextColumn::make('boxDesignation')
                    ->searchable(),
                TextColumn::make('storageType')
                    ->badge()
                    ->searchable(),
                TextColumn::make('rackOrder')
                    ->searchable(),
                TextColumn::make('physicalunits_count')
                    ->counts('physicalunits')
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'info' : 'gray')
                    ->label('Instances'),
                TextColumn::make('created_at')
                    ->dateTime('d M Y - H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime('d M Y - H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()
                    ->visible(fn ($record) => $record->physicalunits()->count() === 0),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
