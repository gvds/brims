<?php

namespace App\Filament\Project\Resources\Subjects\RelationManagers;

use App\Filament\Project\Resources\Subjects\Resources\Events\EventResource;
use App\Filament\Project\Resources\Subjects\Resources\Events\Tables\EventsTable;
use App\Models\Event;
use App\Models\SubjectEvent;
use Filament\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class EventsRelationManager extends RelationManager
{
    protected static string $relationship = 'events';

    protected static ?string $relatedResource = EventResource::class;

    protected $listeners = ['refreshSubjectViewData' => '$refresh'];

    // protected bool $allowsDuplicates = true;

    // public function mount(): void
    // {
    //     $subject = $this->getOwnerRecord();
    //     $subjectEvents = $subject->subjectEvents()->get();
    //     dd($subjectEvents);
    // }

    // public function form(Schema $schema): Schema
    // {
    //     return EventForm::configure($schema);
    // }

    // public function getTableQuery(): Relation | Builder
    // {
    //     return SubjectEvent::query()
    //         ->join('events', 'subject_event.event_id', '=', 'events.id')
    //         // ->join('arms', 'events.arm_id', '=', 'arms.id')
    //         ->select('subject_event.*', 'events.name as event_name')
    //         ->where('subject_event.subject_id', $this->getOwnerRecord()->id);
    // }

    // public function getTableQuery(): Relation | Builder
    // {
    //     return Event::query()
    //         ->join('subject_event', 'events.id', '=', 'subject_event.event_id')
    //         // ->select('subject_event.*', 'events.name as event_name', 'events.id as event_id')
    //         // ->select('subject_event.subject_id as pivot_subject_id', 'subject_event.event_id as pivot_event_id', 'subject_event.id as pivot_id', 'subject_event.iteration as pivot_iteration', 'subject_event.status as pivot_status', 'subject_event.labelstatus as pivot_labelstatus', 'subject_event.eventDate as pivot_eventDate', 'subject_event.minDate as pivot_minDate', 'subject_event.maxDate as pivot_maxDate', 'subject_event.logDate as pivot_logDate', 'subject_event.created_at as pivot_created_at', 'subject_event.updated_at as pivot_updated_at', 'subject_event.*', 'events.id as event_id', 'events.*', 'subject_event.id')
    //         ->select('subject_event.subject_id as pivot_subject_id', 'subject_event.event_id as pivot_event_id', 'subject_event.id as pivot_id', 'subject_event.iteration as pivot_iteration', 'subject_event.status as pivot_status', 'subject_event.labelstatus as pivot_labelstatus', 'subject_event.eventDate as pivot_eventDate', 'subject_event.minDate as pivot_minDate', 'subject_event.maxDate as pivot_maxDate', 'subject_event.logDate as pivot_logDate', 'subject_event.created_at as pivot_created_at', 'subject_event.updated_at as pivot_updated_at', 'subject_event.*', 'events.*')
    //         ->where('subject_event.subject_id', $this->getOwnerRecord()->id);
    // }

    // public function table(Table $table): Table
    // {
    //     // return EventsTable::configure($table);
    //     return $table
    //         ->columns([
    //             TextColumn::make('id'),
    //             TextColumn::make('name')
    //                 ->label('Event Name')
    //                 ->searchable(),
    //             TextColumn::make('eventDate')
    //                 ->date('Y-m-d'),
    //             TextColumn::make('minDate')
    //                 ->date('Y-m-d'),
    //             TextColumn::make('maxDate')
    //                 ->date('Y-m-d'),
    //             TextColumn::make('status')
    //                 ->label('Status'),
    //             TextColumn::make('logDate')
    //                 ->date('Y-m-d'),
    //             TextColumn::make('iteration')
    //                 ->numeric(),
    //             TextColumn::make('labelstatus')
    //                 ->label('Label Status'),
    //         ]);
    // }
}
