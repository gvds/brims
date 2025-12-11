<?php

namespace App\Filament\Resources\Teams\Resources\AssayDefinitions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Schemas\Components\View;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AssayDefinitionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('description')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('user.fullname')
                    ->label('Created By'),
                TextColumn::make('measurementType')
                    ->searchable(),
                // TextColumn::make('measurementTypeTermAccessionNumber')
                //     ->searchable(),
                // TextColumn::make('measurementTypeTermSourceReference')
                //     ->searchable(),
                TextColumn::make('technologyType')
                    ->searchable(),
                // TextColumn::make('technologyTypeTermAccessionNumber')
                //     ->searchable(),
                // TextColumn::make('technologyTypeTermSourceReference')
                //     ->searchable(),
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
