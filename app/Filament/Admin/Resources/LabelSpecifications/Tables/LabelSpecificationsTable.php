<?php

namespace App\Filament\Admin\Resources\LabelSpecifications\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LabelSpecificationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('format')
                    ->searchable(),
                TextColumn::make('paper-size')
                    ->searchable(),
                TextColumn::make('metric')
                    ->searchable(),
                TextColumn::make('marginLeft')
                    ->numeric(),
                TextColumn::make('marginTop')
                    ->numeric(),
                TextColumn::make('NX')
                    ->label('Count X')
                    ->numeric(),
                TextColumn::make('NY')
                    ->label('Count Y')
                    ->numeric(),
                TextColumn::make('SpaceX')
                    ->label('Space X')
                    ->numeric(),
                TextColumn::make('SpaceY')
                    ->label('Space Y')
                    ->numeric(),
                TextColumn::make('width')
                    ->numeric(),
                TextColumn::make('height')
                    ->numeric(),
                TextColumn::make('font-size')
                    ->numeric(),
                TextColumn::make('padding')
                    ->numeric(),
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
