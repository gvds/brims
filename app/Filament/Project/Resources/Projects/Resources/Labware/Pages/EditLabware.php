<?php

namespace App\Filament\Project\Resources\Projects\Resources\Labware\Pages;

use App\Filament\Project\Resources\Projects\Resources\Labware\LabwareResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLabware extends EditRecord
{
    protected static string $resource = LabwareResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
