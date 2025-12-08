<?php

namespace App\Filament\Resources\AssayDefinitions\Pages;

use App\Filament\Resources\AssayDefinitions\AssayDefinitionResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAssayDefinition extends ViewRecord
{
    protected static string $resource = AssayDefinitionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('return')
                ->color('gray')
                ->url(fn(): string => AssayDefinitionResource::getUrl('index')),
            EditAction::make(),
        ];
    }
}
