<?php

namespace App\Filament\App\Resources\Teams\Resources\Protocols\Pages;

use App\Filament\App\Resources\Teams\Resources\Protocols\ProtocolResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateProtocol extends CreateRecord
{
    protected static string $resource = ProtocolResource::class;

    #[\Override]
    protected function handleRecordCreation(array $data): Model
    {
        $data['user_id'] = auth()->id();

        return static::getModel()::create($data);
    }
}
