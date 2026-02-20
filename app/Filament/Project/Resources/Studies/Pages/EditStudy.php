<?php

namespace App\Filament\Project\Resources\Studies\Pages;

use App\Filament\Project\Resources\Studies\StudyResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditStudy extends EditRecord
{
    protected static string $resource = StudyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    public function getRelationManagers(): array
    {
        return [];
    }

    protected function getRedirectUrl(): ?string
    {
        return $this->getResource()::getUrl('view', ['project' => $this->record->project, 'record' => $this->getRecord()]);
    }
}
