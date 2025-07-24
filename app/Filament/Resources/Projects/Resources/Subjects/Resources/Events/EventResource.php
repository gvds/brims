<?php

namespace App\Filament\Resources\Projects\Resources\Subjects\Resources\Events;

use App\Filament\Resources\Projects\Resources\Subjects\Resources\Events\Pages\CreateEvent;
use App\Filament\Resources\Projects\Resources\Subjects\Resources\Events\Pages\EditEvent;
use App\Filament\Resources\Projects\Resources\Subjects\Resources\Events\Schemas\EventForm;
use App\Filament\Resources\Projects\Resources\Subjects\Resources\Events\Tables\EventsTable;
use App\Filament\Resources\Projects\Resources\Subjects\SubjectResource;
use App\Models\Event;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = SubjectResource::class;

    public static function form(Schema $schema): Schema
    {
        return EventForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EventsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'create' => CreateEvent::route('/create'),
            'edit' => EditEvent::route('/{record}/edit'),
        ];
    }
}
