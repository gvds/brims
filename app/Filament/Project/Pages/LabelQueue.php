<?php

namespace App\Filament\Project\Pages;

use App\Enums\LabelStatus;
use App\Enums\SubjectStatus;
use App\Models\SubjectEvent;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class LabelQueue extends Page implements HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?int $navigationSort = 3;

    /**
     * Match parent signature which accepts a BackedEnum as well as string|null.
     */
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-queue-list';

    protected string $view = 'filament.project.pages.label-queue';

    protected function getTableQuery(): Builder
    {
        $userIds = Auth::user()->substitutees->pluck('id')->push(Auth::id())->all();

        return SubjectEvent::query()
            ->with(['subject.user', 'event.arm'])
            ->where('labelstatus', LabelStatus::Queued->value)
            ->whereHas('subject', fn(Builder $q) => $q
                ->where('project_id', session('currentProject')->id)
                ->where('status', SubjectStatus::Enrolled)
                ->whereIn('user_id', $userIds))
            ->orderBy('eventDate');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('subject.subjectID')
                ->label('Subject ID')
                ->searchable(),
            TextColumn::make('subject.firstname')
                ->label('First name')
                ->searchable(),
            TextColumn::make('subject.lastname')
                ->label('Surname')
                ->searchable(),
            TextColumn::make('subject.user.fullname')
                ->label('Manager')
                ->searchable([
                    'firstname',
                    'lastname',
                ]),
            TextColumn::make('event.arm.name')
                ->label('Arm'),
            TextColumn::make('event.name')
                ->label('Event')
                ->searchable(),
            TextColumn::make('iteration')
                ->label('Iteration'),
            TextColumn::make('eventDate')
                ->label('Event date')
                ->date('Y-m-d')
                ->sortable(),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('event.arm_id')
                ->label('Arm')
                ->relationship('event.arm', 'name'),
        ];
    }

    /**
     * Actions shown in the table header (affect the whole table/project).
     */
    protected function getTableHeaderActions(): array
    {
        return [
            Action::make('printAll')
                ->label('Print all')
                ->icon('heroicon-o-printer')
                ->url(fn() => route('labels.print'))
                ->openUrlInNewTab(),
            Action::make('clearAll')
                ->label('Clear all')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Clear all labels from queue')
                ->modalDescription('This will mark all queued labels as generated and remove them from the queue.')
                ->action(fn() => SubjectEvent::where('labelstatus', LabelStatus::Queued->value)->update(['labelstatus' => LabelStatus::Generated->value])),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('clear')
                ->label('Clear from queue')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Clear label from queue')
                ->modalDescription('This will mark the label as generated and remove it from the queue.')
                ->action(fn(SubjectEvent $record) => $record->update(['labelstatus' => LabelStatus::Generated->value])),

            Action::make('print')
                ->label('Print')
                ->icon('heroicon-o-printer')
                ->url(fn(SubjectEvent $record) => route('labels.print', ['ids' => [$record->id]]))
                ->openUrlInNewTab(),
        ];
    }

    protected function getTableBulkActions(): array
    {
        return [
            BulkAction::make('clearSelected')
                ->label('Clear selected')
                ->requiresConfirmation()
                ->action(fn(Collection $records) => $records->each(fn($r) => $r->update(['labelstatus' => LabelStatus::Generated->value]))),

            BulkAction::make('printSelected')
                ->label('Print selected')
                ->action(fn(Collection $records) => redirect()->route('labels.print', ['ids' => $records->pluck('id')->all()])),

        ];
    }
}
