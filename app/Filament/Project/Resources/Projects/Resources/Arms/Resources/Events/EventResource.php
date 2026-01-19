<?php

namespace App\Filament\Project\Resources\Projects\Resources\Arms\Resources\Events;

use App\Filament\Project\Resources\Projects\Resources\Arms\ArmResource;
use App\Filament\Project\Resources\Projects\Resources\Arms\Resources\Events\Pages\CreateEvent;
use App\Filament\Project\Resources\Projects\Resources\Arms\Resources\Events\Pages\EditEvent;
use App\Filament\Project\Resources\Projects\Resources\Arms\Resources\Events\Schemas\EventForm;
use App\Filament\Project\Resources\Projects\Resources\Arms\Resources\Events\Tables\EventsTable;
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

    protected static ?string $parentResource = ArmResource::class;

    #[\Override]
    public static function form(Schema $schema): Schema
    {
        return EventForm::configure($schema);
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return EventsTable::configure($table);
    }

    #[\Override]
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
