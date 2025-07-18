<?php

namespace App\Filament\Resources\Projects\Resources\Studies;

use App\Filament\Resources\Projects\ProjectResource;
use App\Filament\Resources\Projects\Resources\Studies\Pages\CreateStudy;
use App\Filament\Resources\Projects\Resources\Studies\Pages\EditStudy;
use App\Filament\Resources\Projects\Resources\Studies\Pages\ViewStudy;
use App\Filament\Resources\Projects\Resources\Studies\Schemas\StudyForm;
use App\Filament\Resources\Projects\Resources\Studies\Schemas\StudyInfolist;
use App\Filament\Resources\Projects\Resources\Studies\Tables\StudiesTable;
use App\Models\Study;
use BackedEnum;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StudyResource extends Resource
{
    protected static ?string $model = Study::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = ProjectResource::class;

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
            RelationManagers\AssaysRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'create' => CreateStudy::route('/create'),
            'view' => ViewStudy::route('/{record}'),
            'edit' => EditStudy::route('/{record}/edit'),
        ];
    }
}
