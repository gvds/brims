<?php

namespace App\Filament\Resources\Teams\Resources\Protocols\Pages;

use App\Filament\Resources\Teams\Resources\Protocols\ProtocolResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProtocol extends ViewRecord
{
    protected static string $resource = ProtocolResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
