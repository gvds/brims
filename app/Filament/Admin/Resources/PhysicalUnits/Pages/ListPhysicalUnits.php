<?php

namespace App\Filament\Admin\Resources\PhysicalUnits\Pages;

use App\Filament\Admin\Resources\PhysicalUnits\PhysicalUnitResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPhysicalUnits extends ListRecords
{
    protected static string $resource = PhysicalUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
