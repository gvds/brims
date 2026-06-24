<?php

namespace App\Filament\App\Resources\Teams\Resources\Programmes\Pages;

use App\Filament\App\Resources\Teams\Resources\Programmes\ProgrammeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditProgramme extends EditRecord
{
    protected static string $resource = ProgrammeResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
