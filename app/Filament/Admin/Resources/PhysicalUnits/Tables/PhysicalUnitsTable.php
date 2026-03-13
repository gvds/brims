<?php

namespace App\Filament\Admin\Resources\PhysicalUnits\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PhysicalUnitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('unitDefinition.name')
                    ->searchable(),
                TextColumn::make('serial')
                    ->searchable(),
                TextColumn::make('administrator.fullname')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('unitDefinition.storageType')
                    ->label('Storage Type')
                    ->badge()
                    ->searchable(),
                TextColumn::make('virtual_units_count')
                    ->counts('virtualUnits')
                    ->alignCenter()
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'info' : 'gray')
                    ->label('Virtual Units'),
                IconColumn::make('available')
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
                TernaryFilter::make('available')
                    ->label('Available'),
            ])
            ->deferFilters(false)
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
