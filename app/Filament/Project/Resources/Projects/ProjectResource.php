<?php

namespace App\Filament\Project\Resources\Projects;

use App\Filament\Project\Resources\Projects\Pages\EditProject;
use App\Filament\Project\Resources\Projects\Pages\ViewProject;
use App\Filament\Project\Resources\Projects\Schemas\ProjectForm;
use App\Filament\Project\Resources\Projects\Schemas\ProjectInfolist;
use App\Models\Project;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    #[\Override]
    public static function form(Schema $schema): Schema
    {
        return ProjectForm::configure($schema);
    }

    #[\Override]
    public static function infolist(Schema $schema): Schema
    {
        return ProjectInfolist::configure($schema);
    }

    #[\Override]
    public static function getRelations(): array
    {
        return [
            RelationManagers\MembersRelationManager::class,
            RelationManagers\SitesRelationManager::class,
            RelationManagers\ArmsRelationManager::class,
            RelationManagers\LabwareRelationManager::class,
            RelationManagers\SpecimentypesRelationManager::class,
            RelationManagers\PublicationsRelationManager::class,
            RelationManagers\ImportValueMappingsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'view' => ViewProject::route('/{record}'),
            'edit' => EditProject::route('/{record}/edit'),
        ];
    }
}
