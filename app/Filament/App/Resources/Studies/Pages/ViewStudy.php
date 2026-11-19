<?php

namespace App\Filament\App\Resources\Studies\Pages;

use App\Filament\App\Resources\Studies\StudyResource;
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
