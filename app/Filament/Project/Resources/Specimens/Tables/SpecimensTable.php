<?php

namespace App\Filament\Project\Resources\Specimens\Tables;

use App\Enums\SpecimenStatus;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class SpecimensTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('barcode')
                    ->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('subjectEvent.event.name')
                    ->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('specimenType.name')
                    ->label('Type')
                    ->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('site.name')
                    ->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('status')
                    ->badge()
                    ->searchable(
                        query: function (Builder $query, string $search): Builder {
                            $matchingStatuses = collect(SpecimenStatus::cases())
                                ->filter(fn($status) => str_contains(strtolower($status->getLabel()), strtolower($search)))
                                ->map(fn($status) => $status->value);

                            return $query->whereIn('status', $matchingStatuses);
                        },
                        isIndividual: true,
                        isGlobal: false
                    ),
                TextColumn::make('aliquot')
                    ->numeric(),
                // TextColumn::make('volume')
                //     ->formatStateUsing(fn($state, Model $record) => $state . $record->volumeUnit),
                // TextColumn::make('thawcount')
                //     ->label('Thaw Count')
                //     ->numeric(),
                TextColumn::make('loggedBy.fullname')
                    ->label('Logged By')
                    ->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('loggedAt')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('loggedOutBy.fullname')
                    ->label('Logged Out By')
                    ->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('usedBy.fullname')
                    ->label('Used By')
                    ->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('parentSpecimen.barcode')
                    ->label('Parent Barcode')
                    ->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('usedAt')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('logOut')
                        ->action(fn(Collection $records) => $records->each->logOut())
                        ->requiresConfirmation(),
                    BulkAction::make('logReturn')
                        ->action(fn(Collection $records) => $records->each->logReturn())
                        ->requiresConfirmation()
                ]),
            ]);
    }
}
