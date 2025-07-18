<?php

namespace App\Filament\Resources\AssayDefinitions;

use App\Filament\Resources\AssayDefinitions\Pages\CreateAssayDefinition;
use App\Filament\Resources\AssayDefinitions\Pages\EditAssayDefinition;
use App\Filament\Resources\AssayDefinitions\Pages\ListAssayDefinitions;
use App\Filament\Resources\AssayDefinitions\Schemas\AssayDefinitionForm;
use App\Filament\Resources\AssayDefinitions\Tables\AssayDefinitionsTable;
use App\Models\AssayDefinition;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AssayDefinitionResource extends Resource
{
    protected static ?string $model = AssayDefinition::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return AssayDefinitionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AssayDefinitionsTable::configure($table);
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
            'index' => ListAssayDefinitions::route('/'),
            'create' => CreateAssayDefinition::route('/create'),
            'edit' => EditAssayDefinition::route('/{record}/edit'),
        ];
    }
}
