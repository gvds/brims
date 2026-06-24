<?php

namespace App\Filament\Admin\Resources\UnitDefinitions\Pages;

use App\Filament\Admin\Resources\UnitDefinitions\UnitDefinitionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUnitDefinition extends CreateRecord
{
    protected static string $resource = UnitDefinitionResource::class;

    protected static bool $canCreateAnother = false;
}
