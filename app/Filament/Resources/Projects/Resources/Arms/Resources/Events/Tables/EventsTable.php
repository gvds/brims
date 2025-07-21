<?php

namespace App\Filament\Resources\Projects\Resources\Arms\Resources\Events\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('redcap_event_id')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('autolog')
                    ->boolean(),
                TextColumn::make('offset')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('offset_ante_window')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('offset_post_window')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('name_labels')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('subject_event_labels')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('study_id_labels')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('event_order')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('repeatable')
                    ->boolean(),
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
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
