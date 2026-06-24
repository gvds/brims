<?php

namespace App\Filament\Admin\Resources\UnitDefinitions\Pages;

use App\Filament\Admin\Resources\UnitDefinitions\RelationManagers\PhysicalunitsRelationManager;
use App\Filament\Admin\Resources\UnitDefinitions\UnitDefinitionResource;
use App\Models\UnitDefinition;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewUnitDefinition extends ViewRecord
{
    protected static string $resource = UnitDefinitionResource::class;

    #[\Override]
    public function getTitle(): string
    {
        return 'Unit Definition: ' . $this->record->name;
    }

    #[\Override]
    protected function getAllRelationManagers(): array
    {
        return [
            PhysicalunitsRelationManager::class,
        ];
    }

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->visible(fn(UnitDefinition $record): bool => $record->physicalunits()->count() === 0),
        ];
    }
}
