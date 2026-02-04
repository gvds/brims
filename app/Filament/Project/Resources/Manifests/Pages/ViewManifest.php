<?php

namespace App\Filament\Project\Resources\Manifests\Pages;

use App\Filament\Project\Resources\Manifests\ManifestResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewManifest extends ViewRecord
{
    protected static string $resource = ManifestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
