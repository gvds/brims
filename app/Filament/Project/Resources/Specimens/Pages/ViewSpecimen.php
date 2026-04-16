<?php

namespace App\Filament\Project\Resources\Specimens\Pages;

use App\Filament\Project\Resources\Specimens\SpecimenResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSpecimen extends ViewRecord
{
    protected static string $resource = SpecimenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
