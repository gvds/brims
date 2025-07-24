<?php

namespace App\Filament\Resources\Projects\Resources\Subjects\Resources\Events\Pages;

use App\Filament\Resources\Projects\Resources\Subjects\Resources\Events\EventResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;
}
