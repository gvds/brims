<?php

namespace App\Filament\Admin\Resources\Studydesigns;

use App\Filament\Admin\Resources\Studydesigns\Pages\CreateStudydesign;
use App\Filament\Admin\Resources\Studydesigns\Pages\EditStudydesign;
use App\Filament\Admin\Resources\Studydesigns\Pages\ListStudydesigns;
use App\Filament\Admin\Resources\Studydesigns\Schemas\StudydesignForm;
use App\Filament\Admin\Resources\Studydesigns\Tables\StudydesignsTable;
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

    protected static ?string $recordTitleAttribute = 'type';

    protected static ?int $navigationSort = 4;

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
            'index' => ListStudydesigns::route('/'),
            'create' => CreateStudydesign::route('/create'),
            'edit' => EditStudydesign::route('/{record}/edit'),
        ];
    }
}
