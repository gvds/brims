<?php

namespace App\Filament\Resources\Teams\Resources\AssayDefinitions;

use App\Filament\Resources\Teams\Resources\AssayDefinitions\Pages\CreateAssayDefinition;
use App\Filament\Resources\Teams\Resources\AssayDefinitions\Pages\EditAssayDefinition;
use App\Filament\Resources\Teams\Resources\AssayDefinitions\Pages\ListAssayDefinitions;
use App\Filament\Resources\Teams\Resources\AssayDefinitions\Pages\ViewAssayDefinition;
use App\Filament\Resources\Teams\Resources\AssayDefinitions\Schemas\AssayDefinitionForm;
use App\Filament\Resources\Teams\Resources\AssayDefinitions\Schemas\AssayDefinitionInfolist;
use App\Filament\Resources\Teams\Resources\AssayDefinitions\Tables\AssayDefinitionsTable;
use App\Filament\Resources\Teams\TeamResource;
use App\Models\AssayDefinition;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class AssayDefinitionResource extends Resource
{
    protected static ?string $model = AssayDefinition::class;

    protected static ?string $parentResource = TeamResource::class;

    protected static ?string $recordTitleAttribute = 'name';

    // protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    #[\Override]
    public static function form(Schema $schema): Schema
    {
        return AssayDefinitionForm::configure($schema);
    }

    #[\Override]
    public static function infolist(Schema $schema): Schema
    {
        return AssayDefinitionInfolist::configure($schema);
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return AssayDefinitionsTable::configure($table);
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
            'index' => ListAssayDefinitions::route('/'),
            'create' => CreateAssayDefinition::route('/create'),
            'edit' => EditAssayDefinition::route('/{record}/edit'),
            'view' => ViewAssayDefinition::route('/{record}/view'),
        ];
    }
}
