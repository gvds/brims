<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use App\Enums\StorageDestinations;
use App\Models\Specimentype;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->autofocus(),
                Grid::make(2)
                    ->schema([
                        Toggle::make('primary')
                            ->required()
                            ->inline(false),
                        Select::make('parentSpecimenType_id')
                            ->relationship(
                                name: 'parentSpecimenType',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn(Builder $query) => $query->where('project_id', $this->ownerRecord->id)
                            )
                            ->searchable()
                            ->preload()
                            ->default(null)
                            ->requiredIf('primary', false)
                            ->prohibitedIf('primary', true),
                    ])
                    ->columnSpanFull(),
                TextInput::make('aliquots')
                    ->required()
                    ->integer()
                    ->minValue(1),
                Toggle::make('pooled')
                    ->required()
                    ->inline(false),
                TextInput::make('defaultVolume')
                    ->numeric()
                    ->default(null)
                    ->requiredWith('volumeUnit'),
                TextInput::make('volumeUnit')
                    ->default(null)
                    ->requiredWith('defaultVolume'),
                TextInput::make('specimenGroup')
                    ->default(null),
                Select::make('labware_id')
                    ->relationship('labware', 'name')
                    ->required(),
                Fieldset::make('Storage')
                    ->schema([
                        Toggle::make('store')
                            ->required()
                            ->inline(false),
                        TextInput::make('storageSpecimenType')
                            ->default(null)
                            ->requiredIf('store', true)
                            ->prohibitedIf('store', false),
                        Select::make('storageDestination')
                            ->label('Destination')
                            ->options(StorageDestinations::class)
                            ->default(null)
                            ->requiredIf('store', true)
                            ->prohibitedIf('store', false),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
                Repeater::make('transferDestinations')
                    ->table([
                        TableColumn::make('Destination'),
                    ])
                    ->schema([
                        TextInput::make('destination'),
                    ])
                    ->columnSpanFull(),
                Toggle::make('active')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable(isIndividual: true, isGlobal: false),
                IconColumn::make('primary')
                    ->boolean(),
                TextColumn::make('aliquots')
                    ->numeric(),
                IconColumn::make('pooled')
                    ->boolean(),
                TextColumn::make('defaultVolume')
                    ->formatStateUsing(fn(Specimentype $record): string => $record->defaultVolume . ' ' . $record->volumeUnit),
                TextColumn::make('specimenGroup')
                    ->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('labware.name')
                    ->searchable(isIndividual: true, isGlobal: false),
                IconColumn::make('store')
                    ->boolean(),
                TextColumn::make('storageDestination')
                    ->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('storageSpecimenType')
                    ->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('parentSpecimenType.name')
                    ->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('transferDestinations')
                    ->formatStateUsing(fn($state) => collect($state)->implode(', '))
                    ->listWithLineBreaks()
                    ->searchable(isIndividual: true, isGlobal: false),
                IconColumn::make('active')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('primary')
                    ->label('Primary'),
            ])
            ->deferFilters(false)
            ->headerActions([
                CreateAction::make(),
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
}
