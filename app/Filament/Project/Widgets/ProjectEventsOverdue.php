<?php

namespace App\Filament\Project\Widgets;

use App\Enums\EventStatus;
use App\Models\ProjectMember;
use App\Models\SubjectEvent;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ProjectEventsOverdue extends TableWidget
{
    // protected static ?string $heading = 'Overdue Events';

    use HasWidgetShield;

    public function table(Table $table): Table
    {
        $substitutees = ProjectMember::where('substitute_id', Auth::id())
            ->pluck('user_id');

        return $table
            ->description('Click on a row to access the subject record')
            ->query(
                fn(): Builder => SubjectEvent::query()
                    ->whereIn('status', [EventStatus::Pending, EventStatus::Primed, EventStatus::Scheduled])
                    ->where('maxDate', '<', today())
                    ->whereHas('subject', fn(Builder $query) => $query->whereIn('user_id', $substitutees->push(Auth::id())))
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
            ->emptyStateHeading('')
            ->recordUrl(
                // fn(SubjectEvent $record) => dd(session('currentProject')->id)
                fn(SubjectEvent $record): string => route('filament.project.resources.subjects.view', parameters: ['tenant' => session('currentProject')->id, 'record' => $record->subject_id])
            );
    }
}
