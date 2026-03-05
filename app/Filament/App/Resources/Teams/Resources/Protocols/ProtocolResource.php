<?php

namespace App\Filament\Resources\Teams\Resources\Protocols;

use App\Filament\Resources\Teams\Resources\Protocols\Pages\CreateProtocol;
use App\Filament\Resources\Teams\Resources\Protocols\Pages\EditProtocol;
use App\Filament\Resources\Teams\Resources\Protocols\Pages\ViewProtocol;
use App\Filament\Resources\Teams\Resources\Protocols\Schemas\ProtocolForm;
use App\Filament\Resources\Teams\Resources\Protocols\Schemas\ProtocolInfolist;
use App\Filament\Resources\Teams\Resources\Protocols\Tables\ProtocolsTable;
use App\Filament\Resources\Teams\TeamResource;
use App\Models\Protocol;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProtocolResource extends Resource
{
    protected static ?string $model = Protocol::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = TeamResource::class;

    protected static ?string $recordTitleAttribute = 'name';

    #[\Override]
    public static function form(Schema $schema): Schema
    {
        return ProtocolForm::configure($schema);
    }

    #[\Override]
    public static function infolist(Schema $schema): Schema
    {
        return ProtocolInfolist::configure($schema);
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return ProtocolsTable::configure($table);
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
            'create' => CreateProtocol::route('/create'),
            'view' => ViewProtocol::route('/{record}'),
            'edit' => EditProtocol::route('/{record}/edit'),
        ];
    }
}
