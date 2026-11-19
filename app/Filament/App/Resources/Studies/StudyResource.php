<?php

namespace App\Filament\App\Resources\Studies;

use App\Filament\App\Resources\Studies\Pages\CreateStudy;
use App\Filament\App\Resources\Studies\Pages\EditStudy;
use App\Filament\App\Resources\Studies\Pages\ListStudies;
use App\Filament\App\Resources\Studies\Pages\ViewStudy;
use App\Filament\App\Resources\Studies\Schemas\StudyForm;
use App\Filament\App\Resources\Studies\Schemas\StudyInfolist;
use App\Filament\App\Resources\Studies\Tables\StudiesTable;
use App\Models\Study;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StudyResource extends Resource
{
    protected static ?string $model = Study::class;

    public static ?int $navigationSort = 9;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    public static function form(Schema $schema): Schema
    {
        return StudyForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StudyInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StudiesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SpecimensRelationManager::class,
            RelationManagers\AssaysRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStudies::route('/'),
            'create' => CreateStudy::route('/create'),
            'view' => ViewStudy::route('/{record}'),
            'edit' => EditStudy::route('/{record}/edit'),
        ];
    }
}
