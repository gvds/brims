<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use App\Models\SubjectEvent;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class EventsDue extends TableWidget
{
    // protected static ?string $heading = 'Events Due';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn(): Builder => Project::query()
                    ->whereRelation('members', 'user_id', auth()->id())
                    ->whereHas(
                        'subjects.subjectEvents',
                        fn(Builder $query) => $query->whereIn('status', [0, 1, 2])
                            ->where('minDate', '<=', today())
                            ->where('maxDate', '>=', today())
                    )
            )
            ->columns([
                TextColumn::make('title')
                    ->label('Project')
                    ->action(function (Project $record) {
                        session(['currentProject' => $record]);
                        return redirect()->route('filament.project.pages.dashboard');
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
                            ->whereIn('status', [0, 1, 2])
                            ->where('minDate', '<=', today())
                            ->where('maxDate', '>=', today())
                            ->count()
                    ),
            ])
            ->paginated(false);
    }
}
