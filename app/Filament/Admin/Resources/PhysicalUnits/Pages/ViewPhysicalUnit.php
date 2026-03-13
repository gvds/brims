<?php

namespace App\Filament\Admin\Resources\PhysicalUnits\Pages;

use App\Filament\Admin\Resources\PhysicalUnits\PhysicalUnitResource;
use Filament\Resources\Pages\ViewRecord;

class ViewPhysicalUnit extends ViewRecord
{
    protected static string $resource = PhysicalUnitResource::class;

    public function getTitle(): string
    {
        return 'Physical Unit: '.$this->record->name;
    }

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
