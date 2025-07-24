<?php

namespace App\Filament\Resources\Projects\Resources\Arms\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EventsRelationManager extends RelationManager
{
    protected static string $relationship = 'events';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('redcap_event_id')
                    ->numeric()
                    ->default(null),
                Toggle::make('autolog')
                    ->required(),
                TextInput::make('offset')
                    ->numeric()
                    ->default(null),
                TextInput::make('offset_ante_window')
                    ->numeric()
                    ->default(null),
                TextInput::make('offset_post_window')
                    ->numeric()
                    ->default(null),
                TextInput::make('name_labels')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('subject_event_labels')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('study_id_labels')
                    ->required()
                    ->numeric()
                    ->default(0),
                // TextInput::make('event_order')
                //     ->required()
                //     ->numeric()
                //     ->default(0),
                Toggle::make('repeatable')
                    ->required(),
                Toggle::make('active')
                    ->required()
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->reorderable('event_order')
            ->defaultSort('event_order', 'asc')
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('redcap_event_id')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('autolog')
                    ->boolean(),
                TextColumn::make('offset')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('offset_ante_window')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('offset_post_window')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('name_labels')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('subject_event_labels')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('study_id_labels')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('event_order')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('repeatable')
                    ->boolean(),
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
                //
            ])
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
