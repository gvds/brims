<?php

namespace App\Filament\Admin\Resources\PhysicalUnits\Pages;

use App\Filament\Admin\Resources\PhysicalUnits\PhysicalUnitResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPhysicalUnit extends EditRecord
{
    protected static string $resource = PhysicalUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
