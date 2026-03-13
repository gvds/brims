<?php

namespace App\Filament\Admin\Resources\UnitDefinitions\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SectionsRelationManager extends RelationManager
{
    protected static string $relationship = 'sections';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('rows')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(15)
                    ->required(),
                TextInput::make('columns')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(15)
                    ->required(),
                TextInput::make('boxes')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(25)
                    ->required(),
                TextInput::make('positions')
                    ->label('Positions per box')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(500)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('section_number')
            ->columns([
                TextColumn::make('section_number')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('rows')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('columns')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('boxes')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('positions')
                    ->numeric()
                    ->sortable(),
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
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateDataUsing(function (array $data): array {
                        $data['section_number'] = $this->getOwnerRecord()->sections()->count() + 1;

                        return $data;
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->after(function (Model $record) {
                        $this->getOwnerRecord()->sections()->get()->each(function (Model $section) use ($record) {
                            if ($section->section_number > $record->section_number) {
                                $section->decrement('section_number');
                            }
                        });
                    }),
            ]);
    }
}
