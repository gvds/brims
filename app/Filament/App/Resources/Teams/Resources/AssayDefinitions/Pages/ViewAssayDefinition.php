<?php

namespace App\Filament\App\Resources\Teams\Resources\AssayDefinitions\Pages;

use App\Filament\App\Resources\Teams\Resources\AssayDefinitions\AssayDefinitionResource;
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
                ->url(fn (): string => static::getParentResource()::getUrl('view', ['record' => $this->record->team_id, 'relation' => 4])),
            EditAction::make(),
        ];
    }
}
