<?php

namespace App\Filament\Project\Resources\Specimens\Pages;

use App\Filament\Project\Resources\Specimens\SpecimenResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSpecimens extends ListRecords
{
    protected static string $resource = SpecimenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
