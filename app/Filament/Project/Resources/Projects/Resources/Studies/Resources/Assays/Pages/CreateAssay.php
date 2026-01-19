<?php

namespace App\Filament\Project\Resources\Projects\Resources\Studies\Resources\Assays\Pages;

use App\Filament\Project\Resources\Projects\Resources\Studies\Resources\Assays\AssayResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAssay extends CreateRecord
{
    protected static string $resource = AssayResource::class;

    #[\Override]
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
