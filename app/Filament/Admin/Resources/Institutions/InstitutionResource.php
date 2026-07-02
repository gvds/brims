<?php

namespace App\Filament\Admin\Resources\Institutions;

use App\Filament\Admin\Resources\Institutions\Pages\CreateInstitution;
use App\Filament\Admin\Resources\Institutions\Pages\EditInstitution;
use App\Filament\Admin\Resources\Institutions\Pages\ListInstitutions;
use App\Filament\Admin\Resources\Institutions\Schemas\InstitutionForm;
use App\Filament\Admin\Resources\Institutions\Tables\InstitutionsTable;
use App\Models\Institution;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class InstitutionResource extends Resource
{
    protected static ?string $model = Institution::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::BuildingLibrary;

    protected static string | UnitEnum | null $navigationGroup = 'User Management';

    public static function form(Schema $schema): Schema
    {
        return InstitutionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InstitutionsTable::configure($table);
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
            'index' => ListInstitutions::route('/'),
            'create' => CreateInstitution::route('/create'),
            'edit' => EditInstitution::route('/{record}/edit'),
        ];
    }
}
