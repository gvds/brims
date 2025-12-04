<?php

namespace App\Filament\project\Resources\Projects\RelationManagers;

use App\Filament\project\Resources\Projects\Resources\Publications\PublicationResource;
use App\Filament\project\Resources\Projects\Resources\Publications\Schemas\PublicationForm;
use App\Filament\project\Resources\Projects\Resources\Publications\Tables\PublicationsTable;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class PublicationsRelationManager extends RelationManager
{
    protected static string $relationship = 'publications';

    // protected static ?string $relatedResource = PublicationResource::class;

    #[\Override]
    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return PublicationForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return PublicationsTable::configure($table);
    }

    // public function table(Table $table): Table
    // {
    //     return $table
    //         ->headerActions([
    //             CreateAction::make(),
    //         ]);
    // }
}
