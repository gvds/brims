<?php

namespace App\Filament\project\Resources\Projects\Resources\Specimentypes\Schemas;

use App\Enums\StorageDestinations;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class SpecimentypeForm
{
    public static function configure(Schema $schema): Schema
    {
        $SpecimentypeModel = $schema->getRecord();

        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->autofocus(),
                Grid::make(2)
                    ->schema([
                        Toggle::make('primary')
                            ->required()
                            ->live()
                            ->inline(false)
                            ->afterStateUpdated(function (Get $get, Set $set): void {
                                if ($get('primary')) {
                                    $set('parentSpecimenType_id', null);
                                }
                            }),
                        Select::make('parentSpecimenType_id')
                            ->relationship(
                                name: 'parentSpecimenType',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn(Builder $query, $livewire) => $query
                                    ->where('project_id', $livewire->ownerRecord->id)
                                    ->when($SpecimentypeModel, fn(Builder $query) => $query->where('id', '!=', $SpecimentypeModel->id))
                            )
                            ->searchable()
                            ->preload()
                            ->default(null)
                            ->requiredIf('primary', false)
                            ->prohibitedIf('primary', true)
                            ->disabled(fn(Get $get): bool => $get('primary')),
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
}
