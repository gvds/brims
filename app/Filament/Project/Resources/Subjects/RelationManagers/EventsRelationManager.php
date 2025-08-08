<?php

namespace App\Filament\Project\Resources\Subjects\RelationManagers;

use App\Filament\Project\Resources\Subjects\Resources\Events\EventResource;
use Filament\Resources\RelationManagers\RelationManager;

class EventsRelationManager extends RelationManager
{
    protected static string $relationship = 'events';

    protected static ?string $relatedResource = EventResource::class;

    protected $listeners = ['refreshSubjectViewData' => '$refresh'];
}
