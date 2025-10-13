<?php

namespace App\Filament\Project\Resources\Subjects;

use App\Filament\Project\Resources\Subjects\Pages\CreateSubject;
use App\Filament\Project\Resources\Subjects\Pages\EditSubject;
use App\Filament\Project\Resources\Subjects\Pages\ListSubjects;
use App\Filament\Project\Resources\Subjects\Pages\ViewSubject;
use App\Filament\Project\Resources\Subjects\Schemas\SubjectForm;
use App\Filament\Project\Resources\Subjects\Schemas\SubjectInfolist;
use App\Filament\Project\Resources\Subjects\Tables\SubjectsTable;
use App\Models\Subject;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SubjectResource extends Resource
{
    protected static ?string $model = Subject::class;

    public static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    #[\Override]
    public static function form(Schema $schema): Schema
    {
        return SubjectForm::configure($schema);
    }

    #[\Override]
    public static function infolist(Schema $schema): Schema
    {
        return SubjectInfolist::configure($schema);
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return SubjectsTable::configure($table);
    }

    #[\Override]
    public static function getRelations(): array
    {
        return [
            // RelationManagers\EventsRelationManager::class,
            RelationManagers\SubjectEventsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSubjects::route('/'),
            'create' => CreateSubject::route('/create'),
            'view' => ViewSubject::route('/{record}'),
            'edit' => EditSubject::route('/{record}/edit'),
        ];
    }
}
