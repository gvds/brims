<?php

namespace App\Filament\project\Resources\Projects\Resources\Publications\Tables;

use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PublicationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->wrap()
                    ->lineClamp(2),
                TextColumn::make('authors')
                    ->searchable()
                    ->listWithLineBreaks()
                    ->limitList(2),
                TextColumn::make('pubmed_id')
                    ->label('PubMed ID')
                    ->prefix('PMID')
                    ->searchable(),
                TextColumn::make('doi')
                    ->label('DOI')
                    ->searchable(),
                TextColumn::make('publication_date')
                    ->label('Publication Date'),
                TextColumn::make('publication_status')
                    ->label('Publication Status')
                    ->badge()
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
