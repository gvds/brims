<?php

namespace App\Filament\Admin\Resources\UnitDefinitions\Pages;

use App\Filament\Admin\Resources\UnitDefinitions\RelationManagers\SectionsRelationManager;
use App\Filament\Admin\Resources\UnitDefinitions\UnitDefinitionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditUnitDefinition extends EditRecord
{
    protected static string $resource = UnitDefinitionResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return ($parameters['record'] ?? null)?->physicalunits->count() === 0;
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make()
                ->visible(fn (Model $record) => $record->physicalunits->count() === 0),
        ];
    }

    #[\Override]
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('view', ['record' => $this->record]);
    }

    #[\Override]
    protected function getAllRelationManagers(): array
    {
        return [
            SectionsRelationManager::class,
        ];
    }
}
