<?php

namespace App\Filament\project\Resources\Projects\Resources\ImportValueMappings\Pages;

use App\Filament\project\Resources\Projects\Resources\ImportValueMappings\ImportValueMappingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateImportValueMapping extends CreateRecord
{
    protected static string $resource = ImportValueMappingResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getParentResource()::getUrl('view', ['record' => $this->record->project_id]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        return $data;
    }
}
