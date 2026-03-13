<?php

namespace App\Filament\Admin\Resources\PhysicalUnits;

use App\Filament\Admin\Resources\PhysicalUnits\Pages\ListPhysicalUnits;
use App\Filament\Admin\Resources\PhysicalUnits\Pages\ViewPhysicalUnit;
use App\Filament\Admin\Resources\PhysicalUnits\RelationManagers\VirtualUnitsRelationManager;
use App\Filament\Admin\Resources\PhysicalUnits\Schemas\PhysicalUnitForm;
use App\Filament\Admin\Resources\PhysicalUnits\Schemas\PhysicalUnitInfolist;
use App\Filament\Admin\Resources\PhysicalUnits\Tables\PhysicalUnitsTable;
use App\Models\PhysicalUnit;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PhysicalUnitResource extends Resource
{
    protected static ?string $model = PhysicalUnit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return PhysicalUnitForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PhysicalUnitInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PhysicalUnitsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            VirtualUnitsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPhysicalUnits::route('/'),
            'view' => ViewPhysicalUnit::route('/{record}'),
        ];
    }
}
