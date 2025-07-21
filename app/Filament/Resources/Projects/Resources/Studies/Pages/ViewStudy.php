<?php

namespace App\Filament\Resources\Projects\Resources\Studies\Pages;

use App\Filament\Resources\Projects\ProjectResource;
use App\Filament\Resources\Projects\Resources\Studies\StudyResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStudy extends ViewRecord
{
    protected static string $resource = StudyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('return')
                ->label('Return to Studies')
                ->color('gray')
                ->url(fn(): string => ProjectResource::getUrl('view', ['record' => $this->record->project_id])),
            EditAction::make(),
        ];
    }
}
