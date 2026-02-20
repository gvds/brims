<?php

namespace App\Filament\Project\Resources\Studies;

use App\Filament\Project\Resources\Studies\Pages\CreateStudy;
use App\Filament\Project\Resources\Studies\Pages\EditStudy;
use App\Filament\Project\Resources\Studies\Pages\ListStudies;
use App\Filament\Project\Resources\Studies\Pages\ViewStudy;
use App\Filament\Project\Resources\Studies\Schemas\StudyForm;
use App\Filament\Project\Resources\Studies\Schemas\StudyInfolist;
use App\Filament\Project\Resources\Studies\Tables\StudiesTable;
use App\Models\Study;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StudyResource extends Resource
{
    protected static ?string $model = Study::class;

    public static ?int $navigationSort = 7;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    #[\Override]
    public static function form(Schema $schema): Schema
    {
        return StudyForm::configure($schema);
    }

    #[\Override]
    public static function infolist(Schema $schema): Schema
    {
        return StudyInfolist::configure($schema);
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return StudiesTable::configure($table);
    }

    #[\Override]
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
