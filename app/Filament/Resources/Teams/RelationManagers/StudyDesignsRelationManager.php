<?php

namespace App\Filament\Resources\Teams\RelationManagers;

use App\Filament\Resources\Teams\Resources\Studydesigns\Schemas\StudydesignForm;
use App\Filament\Resources\Teams\Resources\Studydesigns\StudydesignResource;
use App\Filament\Resources\Teams\Resources\Studydesigns\Tables\StudydesignsTable;
use App\Models\StudyDesign;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class StudyDesignsRelationManager extends RelationManager
{
    protected static string $relationship = 'studyDesigns';

    // protected static ?string $relatedResource = StudydesignResource::class;

    #[\Override]
    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return StudydesignForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return StudydesignsTable::configure($table)
            ->headerActions([
                CreateAction::make(), // Uncomment if you want to allow creating projects directly from the team relation manager
            ]);
    }
}
