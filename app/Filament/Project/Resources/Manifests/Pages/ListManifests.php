<?php

namespace App\Filament\Project\Resources\Manifests\Pages;

use App\Filament\Project\Resources\Manifests\ManifestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListManifests extends ListRecords
{
    protected static string $resource = ManifestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
        ];
    }
}
