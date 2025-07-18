<?php

namespace App\Filament\Resources\AssayDefinitions\Pages;

use App\Filament\Resources\AssayDefinitions\AssayDefinitionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAssayDefinition extends CreateRecord
{
    protected static string $resource = AssayDefinitionResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
