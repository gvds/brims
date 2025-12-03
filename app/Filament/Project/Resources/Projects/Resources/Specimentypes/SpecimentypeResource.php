<?php

namespace App\Filament\Resources\Projects\Resources\Specimentypes;

use App\Filament\Resources\Projects\ProjectResource;
use App\Filament\Resources\Projects\Resources\Specimentypes\Pages\CreateSpecimentype;
use App\Filament\Resources\Projects\Resources\Specimentypes\Pages\EditSpecimentype;
use App\Filament\Resources\Projects\Resources\Specimentypes\Pages\ViewSpecimentype;
use App\Filament\Resources\Projects\Resources\Specimentypes\Schemas\SpecimentypeForm;
use App\Filament\Resources\Projects\Resources\Specimentypes\Tables\SpecimentypesTable;
use App\Models\Specimentype;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SpecimentypeResource extends Resource
{
    protected static ?string $model = Specimentype::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = ProjectResource::class;

    #[\Override]
    public static function form(Schema $schema): Schema
    {
        return SpecimentypeForm::configure($schema);
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return SpecimentypesTable::configure($table);
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
            'create' => CreateSpecimentype::route('/create'),
            'view' => ViewSpecimentype::route('/{record}'),
            'edit' => EditSpecimentype::route('/{record}/edit'),
        ];
    }
}
