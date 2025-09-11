<?php

namespace App\Filament\Widgets;

use App\Models\Project;
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
            ->query(function (): Builder {
                return Project::query()
                    ->whereHas('subjects.subjectEvents', function (Builder $query) {
                        $query->whereIn('status', [0, 1, 2])
                            ->where('minDate', '<=', today())
                            ->where('maxDate', '>=', today());
                    })
                    ->withCount(['subjects as events_due_count' => function (Builder $query) {
                        $query->whereHas('subjectEvents', function (Builder $subQuery) {
                            $subQuery->whereIn('status', [0, 1, 2])
                                ->where('minDate', '<=', today())
                                ->where('maxDate', '>=', today());
                        });
                    }]);
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
                TextColumn::make('events_due_count')
                    ->label('Events'),
            ]);
    }
}
