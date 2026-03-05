<?php

namespace App\Filament\App\Resources\Teams\Resources\AssayDefinitions\Pages;

use App\Filament\App\Resources\Teams\Resources\AssayDefinitions\AssayDefinitionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAssayDefinition extends EditRecord
{
    protected static string $resource = AssayDefinitionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
