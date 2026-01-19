<?php

namespace App\Filament\Project\Resources\Projects\Resources\Studies\Resources\Assays;

use App\Filament\Project\Resources\Projects\Resources\Studies\Resources\Assays\Schemas\AssayForm;
use App\Filament\Project\Resources\Projects\Resources\Studies\Resources\Assays\Tables\AssaysTable;
use App\Filament\Project\Resources\Projects\Resources\Studies\StudyResource;
use App\Models\Assay;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AssayResource extends Resource
{
    protected static ?string $model = Assay::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = StudyResource::class;

    protected static ?string $tenantOwnershipRelationshipName = 'project';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    #[\Override]
    public static function form(Schema $schema): Schema
    {
        return AssayForm::configure($schema);
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return AssaysTable::configure($table);
    }

    #[\Override]
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    #[\Override]
    public static function getPages(): array
    {
        return [
            // 'index' => ListAssays::route('/'),
            // 'create' => CreateAssay::route('/create'),
            // 'edit' => EditAssay::route('/{record}/edit'),
        ];
    }
}
