<?php

namespace App\Filament\Resources\AssayDefinitions\Pages;

use App\Filament\Resources\AssayDefinitions\AssayDefinitionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAssayDefinitions extends ListRecords
{
    protected static string $resource = AssayDefinitionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
