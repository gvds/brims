<?php

namespace App\Filament\Project\Resources\Subjects\RelationManagers;

use App\Filament\Project\Resources\Subjects\Resources\Events\EventResource;
use App\Filament\Project\Resources\Subjects\Resources\Events\Schemas\EventForm;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class EventsRelationManager extends RelationManager
{
    protected static string $relationship = 'events';

    protected static ?string $relatedResource = EventResource::class;

    protected $listeners = ['refreshSubjectViewData' => '$refresh'];


    public function form(Schema $schema): Schema
    {
        return EventForm::configure($schema);
    }

    // public function table(Table $table): Table
    // {
    //     return $table
    //         ->headerActions([
    //             CreateAction::make(),
    //         ]);
    // }
}
