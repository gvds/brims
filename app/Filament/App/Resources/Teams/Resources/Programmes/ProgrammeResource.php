<?php

namespace App\Filament\App\Resources\Teams\Resources\Programmes;

use App\Filament\App\Resources\Teams\Resources\Programmes\Pages\CreateProgramme;
use App\Filament\App\Resources\Teams\Resources\Programmes\Pages\EditProgramme;
use App\Filament\App\Resources\Teams\Resources\Programmes\Pages\ViewProgramme;
use App\Filament\App\Resources\Teams\Resources\Programmes\Schemas\ProgrammeForm;
use App\Filament\App\Resources\Teams\Resources\Programmes\Schemas\ProgrammeInfolist;
use App\Filament\App\Resources\Teams\Resources\Programmes\Tables\ProgrammesTable;
use App\Filament\App\Resources\Teams\TeamResource;
use App\Models\Programme;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProgrammeResource extends Resource
{
    protected static ?string $model = Programme::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = TeamResource::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ProgrammeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProgrammeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProgrammesTable::configure($table);
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
            'create' => CreateProgramme::route('/create'),
            'view' => ViewProgramme::route('/{record}'),
            'edit' => EditProgramme::route('/{record}/edit'),
        ];
    }
}
