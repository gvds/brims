<?php

namespace App\Filament\App\Widgets;

use App\Enums\EventStatus;
use App\Models\Project;
use App\Models\SubjectEvent;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class EventsDue extends TableWidget
{
    // use HasWidgetShield;

    #[\Override]
    public function table(Table $table): Table
    {
        return $table
            ->description('Click on a row to access the project')
            ->query(fn(): Builder => Project::query()->whereKey(array_keys($this->dueCounts())))
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
                    ->getStateUsing(fn(Project $record): int => $this->dueCounts()[$record->id] ?? 0),
            ])
            ->emptyStateHeading('')
            ->paginated(false);
    }

    /**
     * Due event counts keyed by project id, for the projects the user belongs to.
     *
     * @return array<int, int>
     */
    protected function dueCounts(): array
    {
        return Cache::remember(
            'app_events_due.' . Auth::id(),
            120,
            function (): array {
                $subjectManagers = Auth::user()->substitutees
                    ->pluck('users.id')
                    ->push(Auth::id());

                $memberProjectIds = Project::query()
                    ->whereRelation('members', 'user_id', Auth::id())
                    ->pluck('id');

                $subjectEventsTable = (new SubjectEvent)->getTable();

                return SubjectEvent::query()
                    ->whereIn('status', [EventStatus::Pending, EventStatus::Primed, EventStatus::Scheduled])
                    ->where('minDate', '<=', today())
                    ->where('maxDate', '>=', today())
                    ->whereHas('subject', fn(Builder $query) => $query
                        ->whereIn('user_id', $subjectManagers)
                        ->whereIn('project_id', $memberProjectIds))
                    ->selectRaw("(select project_id from subjects where subjects.id = {$subjectEventsTable}.subject_id) as subject_project_id, count(*) as total")
                    ->groupBy('subject_project_id')
                    ->pluck('total', 'subject_project_id')
                    ->map(fn($total): int => (int) $total)
                    ->all();
            }
        );
    }
}
