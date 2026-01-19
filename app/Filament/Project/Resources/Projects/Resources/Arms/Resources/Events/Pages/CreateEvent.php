<?php

namespace App\Filament\Project\Resources\Projects\Resources\Arms\Resources\Events\Pages;

use App\Filament\Project\Resources\Projects\Resources\Arms\Resources\Events\EventResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;
}
