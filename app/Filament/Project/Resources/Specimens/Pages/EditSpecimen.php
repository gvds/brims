<?php

namespace App\Filament\Project\Resources\Specimens\Pages;

use App\Filament\Project\Resources\Specimens\SpecimenResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSpecimen extends EditRecord
{
    protected static string $resource = SpecimenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
