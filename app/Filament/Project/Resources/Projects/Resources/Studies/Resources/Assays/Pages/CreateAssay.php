<?php

namespace App\Filament\project\Resources\Projects\Resources\Studies\Resources\Assays\Pages;

use App\Filament\project\Resources\Projects\Resources\Studies\Resources\Assays\AssayResource;
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
