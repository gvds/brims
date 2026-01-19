<?php

namespace App\Filament\Project\Resources\Projects\RelationManagers;

use App\Filament\Project\Resources\Projects\Resources\Labware\Schemas\LabwareForm;
use App\Filament\Project\Resources\Projects\Resources\Labware\Tables\LabwareTable;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class LabwareRelationManager extends RelationManager
{
    protected static string $relationship = 'labware';

    #[\Override]
    public function isReadOnly(): bool
    {
        return false;
    }


    public function form(Schema $schema): Schema
    {
        return LabwareForm::configure($schema);
    }


    public function table(Table $table): Table
    {
        return LabwareTable::configure($table);
    }
}
