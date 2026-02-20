<?php

namespace App\Filament\Project\Resources\Publications;

use App\Filament\Project\Resources\Publications\Pages\CreatePublication;
use App\Filament\Project\Resources\Publications\Pages\EditPublication;
use App\Filament\Project\Resources\Publications\Pages\ListPublications;
use App\Filament\Project\Resources\Publications\Pages\ViewPublication;
use App\Filament\Project\Resources\Publications\Schemas\PublicationForm;
use App\Filament\Project\Resources\Publications\Schemas\PublicationInfolist;
use App\Filament\Project\Resources\Publications\Tables\PublicationsTable;
use App\Models\Publication;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PublicationResource extends Resource
{
    protected static ?string $model = Publication::class;

    public static ?int $navigationSort = 8;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return PublicationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PublicationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PublicationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPublications::route('/'),
            'create' => CreatePublication::route('/create'),
            'view' => ViewPublication::route('/{record}'),
            'edit' => EditPublication::route('/{record}/edit'),
        ];
    }
}
