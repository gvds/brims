<?php

namespace App\Filament\Project\Resources\Projects\RelationManagers;

use App\Filament\Project\Resources\Projects\Resources\Specimentypes\Schemas\SpecimentypeForm;
use App\Filament\Project\Resources\Projects\Resources\Specimentypes\Tables\SpecimentypesTable;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class SpecimentypesRelationManager extends RelationManager
{
    protected static string $relationship = 'specimentypes';

    protected static ?string $title = 'Specimen Types';

    #[\Override]
    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return SpecimentypeForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return SpecimentypesTable::configure($table);
    }
}
