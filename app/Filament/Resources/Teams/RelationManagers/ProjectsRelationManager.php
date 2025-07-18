<?php

namespace App\Filament\Resources\Teams\RelationManagers;

use App\Filament\Resources\Projects\Schemas\ProjectForm;
use App\Filament\Resources\Projects\Tables\ProjectsTable;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ProjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'projects';

    public function form(Schema $schema): Schema
    {
        return ProjectForm::configure($schema)->extraAttributes(['class' => 'w-full']);
    }

    public function table(Table $table): Table
    {
        return ProjectsTable::configure($table)
            ->headerActions([
                CreateAction::make(), // Uncomment if you want to allow creating projects directly from the team relation manager
            ]);
    }
}
