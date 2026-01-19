<?php

namespace App\Filament\Project\Resources\Projects\Resources\Studies\Resources\Assays\Pages;

use App\Filament\Project\Resources\Projects\Resources\Studies\Resources\Assays\AssayResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAssays extends ListRecords
{
    protected static string $resource = AssayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
