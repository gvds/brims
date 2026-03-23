<?php

namespace App\Filament\Project\Resources\StorageAllocations;

use App\Filament\Project\Resources\StorageAllocations\Pages\ManageStorageAllocations;
use App\Models\StorageAllocation;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StorageAllocationResource extends Resource
{
    protected static ?string $model = StorageAllocation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'created_at';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('storageDestination')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('created_at')
            ->columns([
                TextColumn::make('user.id')
                    ->searchable(),
                TextColumn::make('storageDestination')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageStorageAllocations::route('/'),
        ];
    }
}
