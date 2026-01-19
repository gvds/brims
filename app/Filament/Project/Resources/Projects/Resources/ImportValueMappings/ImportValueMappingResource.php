<?php

namespace App\Filament\Project\Resources\Projects\Resources\ImportValueMappings;

use App\Filament\Project\Resources\Projects\ProjectResource;
use App\Filament\Project\Resources\Projects\Resources\ImportValueMappings\Pages\CreateImportValueMapping;
use App\Filament\Project\Resources\Projects\Resources\ImportValueMappings\Pages\EditImportValueMapping;
use App\Filament\Project\Resources\Projects\Resources\ImportValueMappings\Schemas\ImportValueMappingForm;
use App\Filament\Project\Resources\Projects\Resources\ImportValueMappings\Tables\ImportValueMappingsTable;
use App\Models\ImportValueMapping;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ImportValueMappingResource extends Resource
{
    protected static ?string $model = ImportValueMapping::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = ProjectResource::class;

    protected static ?string $recordTitleAttribute = 'model';

    public static function form(Schema $schema): Schema
    {
        return ImportValueMappingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ImportValueMappingsTable::configure($table);
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
            'create' => CreateImportValueMapping::route('/create'),
            'edit' => EditImportValueMapping::route('/{record}/edit'),
        ];
    }
}
