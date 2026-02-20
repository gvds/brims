<?php

namespace App\Filament\Project\Resources\Studies\Resources\Assays\Pages;

use App\Filament\Project\Resources\Studies\Resources\Assays\AssayResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAssay extends EditRecord
{
    protected static string $resource = AssayResource::class;

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
