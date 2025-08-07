<?php

namespace App\Filament\Project\Resources\Subjects\Resources\Events\Tables;

use App\Models\Subject;
use App\Models\SubjectEvent;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class EventsTable

{

    public static function configure(Table $table): Table
    {
        return $table
            // ->modifyQueryUsing(fn(Builder $query) => dd($query->get()))
            ->modifyQueryUsing(fn(Builder $query) => $query->join('arms', 'events.arm_id', '=', 'arms.id'))
            ->columns([
                TextColumn::make('id'),
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
                TextColumn::make('status')
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
                // EditAction::make(),
                Action::make('newItteration')
                    ->label('New Iteration')
                    // ->visible(fn(Model $record) => $record->repeatable)
                    ->schema(
                        [
                            DatePicker::make('eventDate')
                                ->default(today()),
                        ]
                    )
                    ->action(function ($livewire, $record, $data) {
                        // dd($record->id, SubjectEvent::where('event_id', $record->id)->max('iteration'), $record->iteration);
                        $subject = $livewire->getOwnerRecord();
                        $subject->events()->attach($record->id, [
                            'iteration' => $record->iteration + 1,
                            'status' => 0,
                            'labelstatus' => 0,
                            'eventDate' => $data['eventDate'],
                            // 'minDate' => now(),
                            // 'maxDate' => now(),
                        ]);
                    })
                    // ->visible(fn($record) => $record->repeatable && SubjectEvent::where('event_id', $record->id)->max('iteration') === $record->iteration)
                    ->requiresConfirmation()
                    ->icon('heroicon-o-plus'),
                ViewAction::make(),
            ]);
        // ->toolbarActions([
        // BulkActionGroup::make([
        //     DeleteBulkAction::make(),
        // ]),
        // ]);
    }
}
