<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use App\Filament\Resources\Projects\Resources\ImportValueMappings\ImportValueMappingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class ImportValueMappingsRelationManager extends RelationManager
{
    protected static string $relationship = 'import_value_mappings';

    protected static ?string $relatedResource = ImportValueMappingResource::class;

    #[\Override]
    public function isReadOnly(): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
