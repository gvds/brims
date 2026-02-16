<?php

namespace App\Filament\Resources\Teams\Resources\Studydesigns;

use App\Filament\Resources\Teams\Resources\Studydesigns\Pages\CreateStudydesign;
use App\Filament\Resources\Teams\Resources\Studydesigns\Pages\EditStudydesign;
use App\Filament\Resources\Teams\Resources\Studydesigns\Schemas\StudydesignForm;
use App\Filament\Resources\Teams\Resources\Studydesigns\Tables\StudydesignsTable;
use App\Filament\Resources\Teams\TeamResource;
use App\Models\StudyDesign;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StudydesignResource extends Resource
{
    protected static ?string $model = StudyDesign::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = TeamResource::class;

    protected static ?string $recordTitleAttribute = 'type';

    #[\Override]
    public static function form(Schema $schema): Schema
    {
        return StudydesignForm::configure($schema);
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return StudydesignsTable::configure($table);
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
            'create' => CreateStudydesign::route('/create'),
            'edit' => EditStudydesign::route('/{record}/edit'),
        ];
    }
}
