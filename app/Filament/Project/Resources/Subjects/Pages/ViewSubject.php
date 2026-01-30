<?php

namespace App\Filament\Project\Resources\Subjects\Pages;

use App\Enums\SubjectStatus;
use App\Filament\Project\Resources\Subjects\SubjectResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSubject extends ViewRecord
{
    protected static string $resource = SubjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->visible(fn($record): bool => $record->status !== SubjectStatus::Generated),
        ];
    }

    protected $listeners = ['refreshSubjectViewData' => '$refresh'];
}
