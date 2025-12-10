<?php

namespace App\Filament\Admin\Resources\Teams\RelationManagers;

use App\Filament\Resources\Teams\Resources\Protocols\ProtocolResource;
use App\Filament\Resources\Teams\Resources\Protocols\Tables\ProtocolsTable;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class ProtocolsRelationManager extends RelationManager
{
    protected static string $relationship = 'protocols';

    protected static ?string $relatedResource = ProtocolResource::class;


    public function isReadOnly(): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return ProtocolsTable::configure($table)
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
