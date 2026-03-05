<?php

namespace App\Filament\App\Resources\Teams\Resources\AssayDefinitions\Pages;

use App\Filament\App\Resources\Teams\Resources\AssayDefinitions\AssayDefinitionResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateAssayDefinition extends CreateRecord
{
    protected static string $resource = AssayDefinitionResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    #[\Override]
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();

        return $data;
    }
}
