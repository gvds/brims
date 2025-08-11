<?php

namespace App\Filament\Project\Resources\Specimens;

use App\Filament\Project\Resources\Specimens\Pages\CreateSpecimen;
use App\Filament\Project\Resources\Specimens\Pages\EditSpecimen;
use App\Filament\Project\Resources\Specimens\Pages\ListSpecimens;
use App\Filament\Project\Resources\Specimens\Schemas\SpecimenForm;
use App\Filament\Project\Resources\Specimens\Tables\SpecimensTable;
use App\Models\Specimen;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SpecimenResource extends Resource
{
    protected static ?string $model = Specimen::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'barcode';

    public static function form(Schema $schema): Schema
    {
        return SpecimenForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SpecimensTable::configure($table);
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
            'index' => ListSpecimens::route('/'),
            'create' => CreateSpecimen::route('/create'),
            'edit' => EditSpecimen::route('/{record}/edit'),
        ];
    }
}
