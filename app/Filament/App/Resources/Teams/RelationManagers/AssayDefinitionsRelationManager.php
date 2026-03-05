<?php

namespace App\Filament\Resources\Teams\RelationManagers;

use App\Filament\Resources\Teams\Resources\AssayDefinitions\AssayDefinitionResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class AssayDefinitionsRelationManager extends RelationManager
{
    protected static string $relationship = 'assayDefinitions';

    protected static ?string $relatedResource = AssayDefinitionResource::class;

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return AssayDefinitionResource::form($schema);
    }

    public function infolist(Schema $schema): Schema
    {
        return AssayDefinitionResource::infolist($schema);
    }

    public function table(Table $table): Table
    {
        return AssayDefinitionResource::table($table);
    }
}
