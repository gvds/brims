<?php

namespace App\Filament\Project\Resources\Studies\Pages;

use App\Filament\Project\Resources\Projects\ProjectResource;
use App\Filament\Project\Resources\Studies\StudyResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStudy extends ViewRecord
{
    protected static string $resource = StudyResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
