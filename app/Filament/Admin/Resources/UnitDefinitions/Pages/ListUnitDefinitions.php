<?php

namespace App\Filament\Admin\Resources\UnitDefinitions\Pages;

use App\Filament\Admin\Resources\UnitDefinitions\UnitDefinitionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUnitDefinitions extends ListRecords
{
    protected static string $resource = UnitDefinitionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
