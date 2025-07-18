<?php

namespace App\Filament\Resources\Projects\Resources\Studies\Pages;

use App\Filament\Resources\Projects\Resources\Studies\StudyResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStudy extends ViewRecord
{
    protected static string $resource = StudyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
