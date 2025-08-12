<?php

namespace App\Filament\Project\Resources\Specimens\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SpecimensTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('barcode'),
                TextColumn::make('subjectEvent.event.name'),
                TextColumn::make('specimenType.name'),
                TextColumn::make('site.name'),
                TextColumn::make('status'),
                TextColumn::make('aliquot')
                    ->numeric(),
                TextColumn::make('volume')
                    ->numeric(),
                TextColumn::make('volumeUnit'),
                TextColumn::make('thawcount')
                    ->label('Thaw Count')
                    ->numeric(),
                TextColumn::make('loggedBy.fullname')
                    ->label('Logged By'),
                TextColumn::make('loggedAt')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
                TextColumn::make('loggedOutBy.fullname'),
                TextColumn::make('usedBy.fullname'),
                TextColumn::make('usedAt')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
                TextColumn::make('parentSpecimen.barcode')
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
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
