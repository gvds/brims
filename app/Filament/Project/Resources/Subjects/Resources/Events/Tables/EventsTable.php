<?php

namespace App\Filament\Project\Resources\Subjects\Resources\Events\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->join('arms', 'events.arm_id', '=', 'arms.id'))
            ->columns([
                TextColumn::make('arm.name'),
                TextColumn::make('name')
                    ->label('Event Name')
                    ->searchable(),
                // TextColumn::make('redcap_event_id')
                //     ->numeric(),
                // IconColumn::make('autolog')
                //     ->boolean(),
                TextColumn::make('eventDate')
                    ->date('Y-m-d'),
                TextColumn::make('minDate')
                    ->date('Y-m-d'),
                TextColumn::make('maxDate')
                    ->date('Y-m-d'),
                TextColumn::make('status')
                    ->label('Status'),
                TextColumn::make('logDate')
                    ->date('Y-m-d'),
                // TextColumn::make('offset')
                //     ->numeric(),
                // TextColumn::make('offset_ante_window')
                //     ->numeric(),
                // TextColumn::make('offset_post_window')
                //     ->numeric(),
                // TextColumn::make('name_labels')
                //     ->numeric(),
                // TextColumn::make('subject_event_labels')
                //     ->numeric(),
                // TextColumn::make('study_id_labels')
                //     ->numeric(),
                TextColumn::make('event_order')
                    ->numeric(),
                IconColumn::make('repeatable')
                    ->boolean(),
                TextColumn::make('iteration')
                    ->numeric(),
                TextColumn::make('labelstatus')
                    ->label('Label Status'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort(fn(Builder $query) => $query
                ->orderBy('arm_num', 'asc')
                ->orderBy('event_order', 'asc')
                ->orderBy('iteration', 'asc'))
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
