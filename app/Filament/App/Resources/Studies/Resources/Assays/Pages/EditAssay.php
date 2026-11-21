<?php

namespace App\Filament\App\Resources\Studies\Resources\Assays\Pages;

use App\Filament\App\Resources\Studies\Resources\Assays\AssayResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAssay extends EditRecord
{
    protected static string $resource = AssayResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    #[\Override]
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
