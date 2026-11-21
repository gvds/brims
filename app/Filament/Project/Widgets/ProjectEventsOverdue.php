<?php

namespace App\Filament\Project\Widgets;

use App\Enums\EventStatus;
use App\Models\SubjectEvent;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ProjectEventsOverdue extends TableWidget
{
    // protected static ?string $heading = 'Overdue Events';

    use HasWidgetShield;

    #[\Override]
    public function table(Table $table): Table
    {
        return $table
            ->description('Click on a row to access the subject record')
            ->query(function (): Builder {
                $overdueEventIds = Cache::remember(
                    'project_events_overdue.' . session('currentProject')->id . '.' . Auth::id(),
                    120,
                    function (): array {
                        $userIds = Auth::user()->substitutees
                            ->pluck('users.id')
                            ->push(Auth::id());

                        return SubjectEvent::query()
                            ->whereIn('status', [EventStatus::Pending, EventStatus::Primed, EventStatus::Scheduled])
                            ->where('maxDate', '<', today())
                            ->whereHas('subject', fn(Builder $query) => $query->whereIn('user_id', $userIds))
                            ->pluck('id')
                            ->all();
                    }
                );

                return SubjectEvent::query()->whereKey($overdueEventIds);
            })
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
