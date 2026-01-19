<?php

namespace App\Filament\Project\Resources\Projects\Resources\ImportValueMappings\Pages;

use App\Filament\Project\Resources\Projects\Resources\ImportValueMappings\ImportValueMappingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditImportValueMapping extends EditRecord
{
    protected static string $resource = ImportValueMappingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getParentResource()::getUrl('view', ['record' => $this->record->project_id]);
    }
}
