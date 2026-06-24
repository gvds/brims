<?php

namespace App\Filament\Admin\Resources\LabelSpecifications;

use App\Filament\Admin\Resources\LabelSpecifications\Pages\CreateLabelSpecification;
use App\Filament\Admin\Resources\LabelSpecifications\Pages\EditLabelSpecification;
use App\Filament\Admin\Resources\LabelSpecifications\Pages\ListLabelSpecifications;
use App\Filament\Admin\Resources\LabelSpecifications\Schemas\LabelSpecificationForm;
use App\Filament\Admin\Resources\LabelSpecifications\Tables\LabelSpecificationsTable;
use App\Models\LabelSpecification;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class LabelSpecificationResource extends Resource
{
    protected static ?string $model = LabelSpecification::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'format';

    protected static string | UnitEnum | null $navigationGroup = 'Utilities';

    public static function form(Schema $schema): Schema
    {
        return LabelSpecificationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LabelSpecificationsTable::configure($table);
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
            'index' => ListLabelSpecifications::route('/'),
            'create' => CreateLabelSpecification::route('/create'),
            'edit' => EditLabelSpecification::route('/{record}/edit'),
        ];
    }
}
