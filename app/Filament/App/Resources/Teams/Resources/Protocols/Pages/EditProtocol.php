<?php

namespace App\Filament\Resources\Teams\Resources\Protocols\Pages;

use App\Filament\Resources\Teams\Resources\Protocols\ProtocolResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditProtocol extends EditRecord
{
    protected static string $resource = ProtocolResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
