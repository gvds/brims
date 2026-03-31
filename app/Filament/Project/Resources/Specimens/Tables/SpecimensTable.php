<?php

namespace App\Filament\Project\Resources\Specimens\Tables;

use App\actions\LogSpecimenStatus;
use App\Enums\SpecimenStatus;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
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
                                ->filter(fn($status): bool => str_contains(strtolower((string) $status->getLabel()), strtolower($search)))
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
                // TextColumn::make('loggedOutBy.fullname')
                //     ->label('Logged Out By')
                //     ->searchable(isIndividual: true, isGlobal: false),
                // TextColumn::make('usedBy.fullname')
                //     ->label('Used By')
                //     ->searchable(isIndividual: true, isGlobal: false),
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
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('logUsed')
                        ->label('Log as Used')
                        ->icon(Heroicon::Cog6Tooth)
                        ->action(function (Collection $records) {
                            try {
                                $specimen_count = (new LogSpecimenStatus())(SpecimenStatus::Used, $records->pluck('barcode')->implode(','));
                                Notification::make()
                                    ->title('Specimens Logged as Used')
                                    ->body($specimen_count . ' specimens have been logged as used.')
                                    ->success()
                                    ->send();
                            } catch (\Throwable $th) {
                                Notification::make()
                                    ->title('Error Logging Specimens as Used')
                                    ->body($th->getMessage())
                                    ->danger()
                                    ->persistent()
                                    ->send();
                            }
                        })
                        ->requiresConfirmation(),
                    BulkAction::make('logOut')
                        ->icon(Heroicon::ArrowUpTray)
                        ->action(function (Collection $records) {
                            try {
                                $specimen_count = (new LogSpecimenStatus())(SpecimenStatus::LoggedOut, $records->pluck('barcode')->implode(','));
                                Notification::make()
                                    ->title('Specimens Logged as Logged Out')
                                    ->body($specimen_count . ' specimens have been logged as logged out.')
                                    ->success()
                                    ->send();
                            } catch (\Throwable $th) {
                                Notification::make()
                                    ->title('Error Logging Specimens as Logged Out')
                                    ->body($th->getMessage())
                                    ->danger()
                                    ->persistent()
                                    ->send();
                            }
                        })
                        ->requiresConfirmation(),
                    BulkAction::make('logReturn')
                        ->icon(Heroicon::ArrowDownTray)
                        ->schema([
                            Toggle::make('thawed')
                                ->label('Increment thaw count?')
                                ->default(true)
                                ->onColor('success')
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $thawed = $data['thawed'] ?? false;
                            try {
                                $specimen_count = (new LogSpecimenStatus())(SpecimenStatus::InStorage, $records->pluck('barcode')->implode(','), $thawed);
                                Notification::make()
                                    ->title('Specimens Logged as Returned to Storage')
                                    ->body($specimen_count . ' specimens have been logged as returned to storage.')
                                    ->success()
                                    ->send();
                            } catch (\Throwable $th) {
                                Notification::make()
                                    ->title('Error Logging Specimens as Returned to Storage')
                                    ->body($th->getMessage())
                                    ->danger()
                                    ->persistent()
                                    ->send();
                            }
                        })
                        ->requiresConfirmation(),
                    DeleteBulkAction::make()
                        ->label('Delete'),
                ]),
            ]);
    }
}
