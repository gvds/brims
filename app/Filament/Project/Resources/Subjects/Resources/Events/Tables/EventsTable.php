<?php

namespace App\Filament\Project\Resources\Subjects\Resources\Events\Tables;

use App\Enums\EventStatus;
use App\Enums\SubjectStatus;
use App\Models\Event;
use App\Models\Subject;
use App\Models\SubjectEvent;
use Carbon\CarbonImmutable;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\View;
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
                EditAction::make(),
                Action::make('newItteration')
                    ->label('New Iteration')
                    ->schema(
                        [
                            DatePicker::make('eventDate')
                                ->default(today()),
                        ]
                    )
                    ->action(function ($livewire, $record, $data): void {
                        $eventDate = new CarbonImmutable($data['eventDate']);
                        $subject = $livewire->getOwnerRecord();
                        $subject->addEventIteration($record, $eventDate);
                    })
                    ->visible(
                        fn($record, $livewire) => $record->repeatable &&
                            $record->status !== EventStatus::Cancelled &&
                            $record->iteration === SubjectEvent::where('event_id', $record->id)->max('iteration') &&
                            $record->eventDate > SubjectEvent::where('event_id', $record->id)->whereIn('status', [EventStatus::Logged, EventStatus::LoggedLate])->max('eventDate') &&
                            $livewire->getOwnerRecord()->status === SubjectStatus::Enrolled
                    )
                    ->requiresConfirmation()
                    ->icon('heroicon-o-plus'),
                ViewAction::make(),
                Action::make('logEvent')
                    ->schema(
                        [
                            DatePicker::make('logDate')
                                ->default(today())
                                ->required()
                                ->beforeOrEqual('today')
                                ->afterOrEqual(fn($livewire) => $livewire->getOwnerRecord()->armBaselineDate)
                                ->label('Log Date'),
                        ]
                    )
                    ->action(function ($record, $data): void {
                        $record->log($data);
                    })
                    ->button()
                    ->color('info')
                    ->extraAttributes(['class' => 'py-1'])
                    ->requiresConfirmation()
                    ->visible(
                        fn($record) => $record->status === EventStatus::Scheduled
                    ),
            ]);
        // ->toolbarActions([
        // BulkActionGroup::make([
        //     DeleteBulkAction::make(),
        // ]),
        // ]);
    }
}
