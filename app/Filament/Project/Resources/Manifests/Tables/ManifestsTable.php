<?php

namespace App\Filament\Project\Resources\Manifests\Tables;

use App\Enums\ManifestStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ManifestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->searchable(isIndividual: true),
                TextColumn::make('user.fullname')
                    ->searchable(['firstname', 'lastname'], isIndividual: true),
                TextColumn::make('sourceSite.name')
                    ->searchable(isIndividual: true),
                TextColumn::make('destinationSite.name')
                    ->searchable(isIndividual: true),
                TextColumn::make('shippedDate')
                    ->date('Y-m-d')
                    ->searchable(isIndividual: true)
                    ->sortable(),
                TextColumn::make('receivedBy.fullname')
                    ->searchable(['firstname', 'lastname'], isIndividual: true),
                TextColumn::make('receivedDate')
                    ->date('Y-m-d')
                    ->searchable(isIndividual: true)
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(ManifestStatus::class),
            ])
            ->deferFilters(false)
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
