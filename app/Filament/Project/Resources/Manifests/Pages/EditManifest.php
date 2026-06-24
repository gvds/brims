<?php

namespace App\Filament\Project\Resources\Manifests\Pages;

use App\Filament\Project\Resources\Manifests\ManifestResource;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditManifest extends EditRecord
{
    protected static string $resource = ManifestResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
        ];
    }

    #[\Override]
    public function getRelationManagers(): array
    {
        return [];
    }

    #[\Override]
    protected function getRedirectUrl(): ?string
    {
        return $this->getResource()::getUrl('view', [
            'record' => $this->getRecord(),
        ]);
    }
}
