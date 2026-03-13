<?php

namespace App\Filament\Admin\Resources\UnitDefinitions\RelationManagers;

use App\Filament\Admin\Resources\UnitDefinitions\UnitDefinitionResource;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PhysicalunitsRelationManager extends RelationManager
{
    protected static string $relationship = 'physicalunits';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->sections()->count() > 0;
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('serial')
                    ->searchable(),
                TextColumn::make('administrator.fullname')
                    ->label('Administrator')
                    ->searchable(),
                TextColumn::make('virtual_units_count')
                    ->label('Virtual Units')
                    ->counts('virtualUnits'),
                IconColumn::make('available')
                    ->boolean(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(40)
                            ->unique(ignoreRecord: true),
                        TextInput::make('serial')
                            ->maxLength(30),
                        Select::make('user_id')
                            ->relationship('administrator', 'username')
                            ->required(),
                        Toggle::make('available')
                            ->default(true),
                    ])
                    ->createAnother(false)
                    ->visible(fn () => $this->getOwnerRecord()->sections()->count() > 0)
                    ->mutateDataUsing(function (array $data): array {
                        $data['unitDefinition_id'] = $this->getOwnerRecord()->getKey();

                        return $data;
                    })
                    ->after(fn () => $this->redirect(UnitDefinitionResource::getUrl('view', ['record' => $this->getOwnerRecord()]))),
            ])
            ->recordUrl(fn ($record) => route('filament.admin.resources.physical-units.view', ['record' => $record]))
            ->recordActions([
                EditAction::make()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(40)
                            ->unique(ignoreRecord: true),
                        TextInput::make('serial')
                            ->maxLength(30),
                        Select::make('user_id')
                            ->relationship('administrator', 'username')
                            ->required(),
                        Toggle::make('available'),
                    ]),
                DeleteAction::make()
                    ->visible(fn ($record) => $record->virtualUnits()->count() === 0)
                    ->after(fn () => $this->redirect(UnitDefinitionResource::getUrl('view', ['record' => $this->getOwnerRecord()]))),
            ]);
    }
}
