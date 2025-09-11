<?php

namespace App\Filament\Project\Widgets;

use App\Models\SubjectEvent;
use Dom\Text;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
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
            ->description('Click on a row to access the subject details')
            ->query(
                fn(): Builder => SubjectEvent::query()
                    ->whereIn('status', [0, 1, 2])
                    ->where('maxDate', '<', today())
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
            ->recordUrl(
                fn(SubjectEvent $record): string => route('filament.project.resources.subjects.view', $record->subject_id)
            );
    }
}
