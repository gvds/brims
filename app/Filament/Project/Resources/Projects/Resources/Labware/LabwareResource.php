<?php

namespace App\Filament\Project\Resources\Projects\Resources\Labware;

use App\Filament\Project\Resources\Projects\ProjectResource;
use App\Filament\Project\Resources\Projects\Resources\Labware\Pages\CreateLabware;
use App\Filament\Project\Resources\Projects\Resources\Labware\Pages\EditLabware;
use App\Filament\Project\Resources\Projects\Resources\Labware\Schemas\LabwareForm;
use App\Filament\Project\Resources\Projects\Resources\Labware\Tables\LabwareTable;
use App\Models\Labware;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LabwareResource extends Resource
{
    protected static ?string $model = Labware::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = ProjectResource::class;


    #[\Override]
    public static function form(Schema $schema): Schema
    {
        return LabwareForm::configure($schema);
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return LabwareTable::configure($table);
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
            'create' => CreateLabware::route('/create'),
            'edit' => EditLabware::route('/{record}/edit'),
        ];
    }
}
