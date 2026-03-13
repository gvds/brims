<?php

namespace App\Filament\Admin\Resources\UnitDefinitions;

use App\Filament\Admin\Resources\UnitDefinitions\Pages\CreateUnitDefinition;
use App\Filament\Admin\Resources\UnitDefinitions\Pages\EditUnitDefinition;
use App\Filament\Admin\Resources\UnitDefinitions\Pages\ListUnitDefinitions;
use App\Filament\Admin\Resources\UnitDefinitions\Pages\ViewUnitDefinition;
use App\Filament\Admin\Resources\UnitDefinitions\Schemas\UnitDefinitionForm;
use App\Filament\Admin\Resources\UnitDefinitions\Schemas\UnitDefinitionInfolist;
use App\Filament\Admin\Resources\UnitDefinitions\Tables\UnitDefinitionsTable;
use App\Models\UnitDefinition;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UnitDefinitionResource extends Resource
{
    protected static ?string $model = UnitDefinition::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return UnitDefinitionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return UnitDefinitionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UnitDefinitionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUnitDefinitions::route('/'),
            'create' => CreateUnitDefinition::route('/create'),
            'view' => ViewUnitDefinition::route('/{record}'),
            'edit' => EditUnitDefinition::route('/{record}/edit'),
        ];
    }
}
