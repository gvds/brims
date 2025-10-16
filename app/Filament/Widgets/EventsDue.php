<?php

namespace App\Filament\Widgets;

use App\Enums\EventStatus;
use App\Models\Project;
use App\Models\SubjectEvent;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class EventsDue extends TableWidget
{

    use HasWidgetShield;

    public function table(Table $table): Table
    {
        return $table
            ->description('Click on a row to access the project')
            ->query(
                fn(): Builder => Project::query()
                    ->whereRelation('members', 'user_id', auth()->id())
                    ->whereHas(
                        'subjects.subjectEvents',
                        fn(Builder $query) => $query->whereIn('status', [EventStatus::Pending, EventStatus::Primed, EventStatus::Scheduled])
                            ->where('minDate', '<=', today())
                            ->where('maxDate', '>=', today())
                            ->whereRelation('subject', 'user_id', '=', auth()->id())
                    )
            )
            ->columns([
                TextColumn::make('title')
                    ->label('Project')
                    ->action(function (Project $record) {
                        session(['currentProject' => $record]);
                        return to_route('filament.project.pages.dashboard', parameters: ['tenant' => $record->id]);
                    })
                    ->color('primary')
                    ->weight('bold')
                    ->size('md'),
                TextColumn::make('events_due_count')
                    ->label('Events')
                    ->getStateUsing(
                        fn(Project $record) => SubjectEvent::whereHas(
                            'subject',
                            fn(Builder $query) => $query->where('project_id', $record->id)
                                ->where('user_id', auth()->id())
                        )
                            ->whereIn('status', [EventStatus::Pending, EventStatus::Primed, EventStatus::Scheduled])
                            ->where('minDate', '<=', today())
                            ->where('maxDate', '>=', today())
                            ->count()
                    ),
            ])
            ->emptyStateHeading('')
            ->paginated(false);
    }
}
