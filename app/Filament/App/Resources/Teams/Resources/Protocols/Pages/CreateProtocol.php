<?php

namespace App\Filament\App\Resources\Teams\Resources\Protocols\Pages;

use App\Filament\App\Resources\Teams\Resources\Protocols\ProtocolResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CreateProtocol extends CreateRecord
{
    protected static string $resource = ProtocolResource::class;

    #[\Override]
    protected function handleRecordCreation(array $data): Model
    {
        $data['user_id'] = Auth::id();
        $data['team_id'] = Auth::user()->team_id;

        return static::getModel()::create($data);
    }
}
