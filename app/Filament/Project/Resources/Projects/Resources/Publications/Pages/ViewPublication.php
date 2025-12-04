<?php

namespace App\Filament\project\Resources\Projects\Resources\Publications\Pages;

use App\Filament\project\Resources\Projects\Resources\Publications\PublicationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPublication extends ViewRecord
{
    protected static string $resource = PublicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
