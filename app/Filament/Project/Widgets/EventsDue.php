<?php

namespace App\Filament\Project\Widgets;

use App\Enums\EventStatus;
use App\Models\Subject;
use App\Models\SubjectEvent;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class EventsDue extends TableWidget
{
    public static function canView(): bool
    {
        return auth()->user()->can('create', Subject::class);
    }

    public function table(Table $table): Table
    {
        return $table
            ->description('Click on a row to access the subject details')
            ->query(
                fn(): Builder => SubjectEvent::query()
                    ->whereIn('status', [EventStatus::Pending, EventStatus::Primed, EventStatus::Scheduled])
                    ->where('minDate', '<=', today())
                    ->where('maxDate', '>=', today())
                    ->whereRelation('subject', 'user_id', auth()->id())
            )
            ->columns([
                TextColumn::make('subject.fullname')
                    ->icon(Heroicon::ChevronDoubleRight),
                TextColumn::make('event.arm.name'),
                TextColumn::make('event.name'),
                TextColumn::make('status'),
                TextColumn::make('eventDate'),
                TextColumn::make('minDate'),
                TextColumn::make('maxDate'),
            ])
            ->paginated(false)
            ->emptyStateHeading('No Events Due')
            ->recordUrl(
                fn(SubjectEvent $record): string => route('filament.project.resources.subjects.view', $record->subject_id)
            );
    }
}
