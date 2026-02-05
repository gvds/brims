<?php

namespace App\Filament\Project\Resources\Manifests\Pages;

use App\Filament\Project\Resources\Manifests\ManifestResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateManifest extends CreateRecord
{
    protected static string $resource = ManifestResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();
        $data['sourceSite_id'] = session('currentProject')->members[Auth::id()]->pivot->site_id;

        return $data;
    }
}
