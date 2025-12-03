<?php

namespace App\Filament\project\Resources\Projects\RelationManagers;

use App\Filament\Resources\Projects\Resources\Studies\StudyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class StudiesRelationManager extends RelationManager
{
    protected static string $relationship = 'studies';

    protected static ?string $relatedResource = StudyResource::class;

    #[\Override]
    public function isReadOnly(): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
