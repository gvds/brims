<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use App\Models\SubjectEvent;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class EventsOverdue extends TableWidget
{
    protected static ?string $heading = 'Overdue Events';

    public function table(Table $table): Table
    {
        return $table
            ->query(function (): Builder {
                return Project::query()
                    ->whereRelation('members', 'user_id', auth()->id())
                    ->whereHas('subjects.subjectEvents', function (Builder $query) {
                        $query->whereIn('status', [0, 1, 2])
                            ->where('maxDate', '<', today());
                    });
            })
            ->columns([
                TextColumn::make('title')
                    ->label('Project')
                    // ->description(fn(Project $record) => 'Events Due: ' . $record->events_due_count)
                    ->action(function (Project $record) {
                        session(['currentProject' => $record]);

                        return redirect()->route('filament.project.pages.dashboard');
                    })
                    ->color('primary')
                    ->weight('bold')
                    ->size('md'),
                TextColumn::make('overdue_events_count')
                    ->label('Events')
                    ->getStateUsing(function (Project $record) {
                        return SubjectEvent::whereHas('subject', function (Builder $query) use ($record) {
                            $query->where('project_id', $record->id)
                                ->where('user_id', auth()->id());
                        })
                            ->whereIn('status', [0, 1, 2])
                            ->where('maxDate', '<', today())
                            ->count();
                    }),
            ])
            ->paginated(false);
    }
}
