<?php

namespace App\Filament\Project\Resources\StorageAllocations;

use App\Filament\Project\Resources\StorageAllocations\Pages\AllocateStorage;
use App\Filament\Project\Resources\StorageAllocations\Pages\ManageStorageAllocations;
use App\Models\StorageAllocation;
use BackedEnum;
use Dom\Text;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
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
                TextColumn::make('id')
                    ->label('ID'),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user.full_name')
                    ->searchable(['firstname', 'lastname']),
                TextColumn::make('storageDestination')
                    ->searchable(),
                TextColumn::make('storage_logs_count')
                    ->label('Specimens Allocated')
                    ->counts('storageLogs'),
                TextColumn::make('updated_at')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                // EditAction::make(),
                // DeleteAction::make(),
                Action::make('Print')
                    ->url(fn(StorageAllocation $record): string => route('storage-allocation-report', ['storageAllocation' => $record->id]))
                    ->icon(Heroicon::OutlinedPrinter)
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageStorageAllocations::route('/'),
            'allocate' => AllocateStorage::route('/allocate'),
        ];
    }
}
