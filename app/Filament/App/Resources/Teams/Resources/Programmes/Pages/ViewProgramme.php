<?php

namespace App\Filament\App\Resources\Teams\Resources\Programmes\Pages;

use App\Filament\App\Resources\Teams\Resources\Programmes\ProgrammeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProgramme extends ViewRecord
{
    protected static string $resource = ProgrammeResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
