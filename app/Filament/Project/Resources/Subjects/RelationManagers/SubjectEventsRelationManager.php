<?php

namespace App\Filament\Project\Resources\Subjects\RelationManagers;

use App\Enums\EventStatus;
use App\Enums\LabelStatus;
use App\Enums\SubjectStatus;
use App\Models\SubjectEvent;
use Carbon\CarbonImmutable;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class SubjectEventsRelationManager extends RelationManager
{
    protected static string $relationship = 'subjectEvents';

    protected $listeners = [
        'refreshSubjectViewData' => 'refreshTable',
    ];

    public function refreshTable(): void
    {
        $this->resetTable();
    }

    public function isReadOnly(): bool
    {
        return false;
    }


    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('subject_id')
                    ->relationship('subject', 'id')
                    ->required(),
                Select::make('event_id')
                    ->relationship('event', 'name')
                    ->required(),
                TextInput::make('iteration')
                    ->required()
                    ->numeric()
                    ->default(1),
                Select::make('status')
                    ->options(EventStatus::class)
                    ->required()
                    ->default(0),
                Select::make('labelstatus')
                    ->options(LabelStatus::class)
                    ->required()
                    ->default(0),
                DatePicker::make('eventDate'),
                DatePicker::make('minDate'),
                DatePicker::make('maxDate'),
                DatePicker::make('logDate'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn(Builder $query) => $query
                    ->join('events', 'subject_event.event_id', '=', 'events.id')
                    ->join('arms', 'events.arm_id', '=', 'arms.id')
                    ->select('subject_event.*')
                    ->orderBy('arms.arm_num', 'asc')
                    ->orderBy('events.event_order', 'asc')
                    ->orderBy('subject_event.iteration', 'asc')
            )
            ->columns([
                TextColumn::make('id')
                    ->label('ID'),
                TextColumn::make('event.arm.name'),
                TextColumn::make('event.name'),
                SelectColumn::make('status')
                    ->options(EventStatus::class)
                    ->label('Status')
                    ->disabled(fn() => (bool) (! Auth::user()->can('Update:Subject'))),
                TextColumn::make('eventDate')
                    ->date('Y-m-d')
                    ->extraAttributes(fn(SubjectEvent $record): array => $record->status->value < EventStatus::Logged->value && $record->maxDate < today() ? ['class' => 'text-red-600 font-bold'] : []),
                TextColumn::make('minDate')
                    ->date('Y-m-d'),
                TextColumn::make('maxDate')
                    ->date('Y-m-d'),
                TextColumn::make('logDate')
                    ->date('Y-m-d'),
                TextColumn::make('event.event_order')
                    ->label('Event Order'),
                IconColumn::make('event.repeatable')
                    ->boolean()
                    ->label('Repeatable'),
                TextColumn::make('iteration')
                    ->numeric(),
                // TextColumn::make('labelstatus')
                //     ->label('Label Status'),
                SelectColumn::make('labelstatus')
                    ->options(LabelStatus::class)
                    ->label('Label Status')
                    ->disabled(fn() => (bool) (! Auth::user()->can('Update:Subject'))),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultKeySort(false)
            ->filters([
                //
            ])
            ->headerActions([
                // CreateAction::make(),
                // AssociateAction::make(),
            ])
            ->recordActions([
                // EditAction::make(),
                // DissociateAction::make(),
                // DeleteAction::make(),
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
                        $record->addEventIteration($eventDate);
                    })
                    ->visible(
                        fn($record, $livewire): bool => $record->event->repeatable &&
                            $record->status !== EventStatus::Cancelled &&
                            $record->iteration === SubjectEvent::where('event_id', $record->event->id)->max('iteration') &&
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
                            Select::make('eventStatus')
                                ->label('Event Status')
                                ->options([
                                    EventStatus::Logged->value => 'Logged',
                                    EventStatus::Missed->value => 'Missed',
                                ]),
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
                        fn($record): bool => $record->status === EventStatus::Scheduled
                    ),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DissociateBulkAction::make(),
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
