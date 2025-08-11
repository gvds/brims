<?php

namespace App\Filament\Resources\Assays;

use App\Filament\Resources\Assays\Pages\CreateAssay;
use App\Filament\Resources\Assays\Pages\EditAssay;
use App\Filament\Resources\Assays\Pages\ListAssays;
use App\Filament\Resources\Assays\Schemas\AssayForm;
use App\Filament\Resources\Assays\Tables\AssaysTable;
use App\Models\Assay;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AssayResource extends Resource
{
    protected static ?string $model = Assay::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    #[\Override]
    public static function form(Schema $schema): Schema
    {
        return AssayForm::configure($schema);
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return AssaysTable::configure($table);
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
            'index' => ListAssays::route('/'),
            'create' => CreateAssay::route('/create'),
            'edit' => EditAssay::route('/{record}/edit'),
        ];
    }
}
