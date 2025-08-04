<?php

namespace App\Filament\Project\Resources\Subjects\Resources\Events\Tables;

use App\Models\Subject;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
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
                TextColumn::make('eventDate')
                    ->date('Y-m-d'),
                TextColumn::make('minDate')
                    ->date('Y-m-d'),
                TextColumn::make('maxDate')
                    ->date('Y-m-d'),
                TextColumn::make('pivot.status')
                    ->label('Status'),
                TextColumn::make('logDate')
                    ->date('Y-m-d'),
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
                // EditAction::make(),
                Action::make('newItteration')
                    ->label('New Iteration')
                    ->schema(
                        [
                            DatePicker::make('eventDate')
                                ->default(today()),
                        ]
                    )
                    ->action(function ($record, $data) {
                        $subject = Subject::find($record->pivot->subject_id);
                        $subject->events()->attach($record->id, [
                            'iteration' => $record->pivot->iteration + 1,
                            'status' => 0,
                            'labelstatus' => 0,
                            'eventDate' => $data['eventDate'],
                            // 'minDate' => now(),
                            // 'maxDate' => now(),
                        ]);
                    })
                    ->requiresConfirmation()
                    ->icon('heroicon-o-plus'),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
