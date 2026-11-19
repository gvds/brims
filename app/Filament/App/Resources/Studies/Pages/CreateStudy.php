<?php

namespace App\Filament\App\Resources\Studies\Pages;

use App\Filament\App\Resources\Studies\StudyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStudy extends CreateRecord
{
    protected static string $resource = StudyResource::class;

    protected static bool $canCreateAnother = false;

    #[\Override]
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['project' => $this->record->project, 'record' => $this->getRecord()]);
    }
}
