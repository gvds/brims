<?php

namespace App\Filament\Widgets;

use App\Enums\EventStatus;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\SubjectEvent;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class EventsOverdue extends TableWidget
{

    // use HasWidgetShield;

    // protected static ?string $heading = 'Overdue Events';

    public function table(Table $table): Table
    {
        $substitutees = Auth::user()->substitutees()
            ->pluck('users.id');

        return $table
            ->description('Click on a row to access the project')
            ->query(
                fn(): Builder => Project::query()
                    ->whereRelation('members', 'user_id', Auth::id())
                    ->whereHas(
                        'subjects.subjectEvents',
                        fn(Builder $query): Builder =>
                        $query->whereIn('status', [EventStatus::Pending, EventStatus::Primed, EventStatus::Scheduled])
                            ->where('maxDate', '<', today())
                            ->whereHas('subject', fn(Builder $query) => $query->whereIn('user_id', $substitutees->push(Auth::id())))
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
                TextColumn::make('overdue_events_count')
                    ->label('Events')
                    ->getStateUsing(fn(Project $record) => SubjectEvent::whereHas('subject', function (Builder $query) use ($record): void {
                        $query->where('project_id', $record->id)
                            ->where('user_id', Auth::id());
                    })
                        ->whereIn('status', [EventStatus::Pending, EventStatus::Primed, EventStatus::Scheduled])
                        ->where('maxDate', '<', today())
                        ->count()),
            ])
            ->emptyStateHeading('')
            ->paginated(false);
    }
}
