<?php

namespace App\Filament\Project\Resources\Manifests\RelationManagers;

use App\Models\Specimen;
use Dom\Text;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;

class SpecimensRelationManager extends RelationManager
{
    protected static string $relationship = 'specimens';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('barcode')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('barcode'),
                TextEntry::make('aliquot'),
                TextEntry::make('thawcount'),
                TextEntry::make('volume')
                    ->formatStateUsing(fn(Specimen $record): string => "{$record->volume}{$record->volumeUnit}"),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('barcode')
            ->columns([
                TextColumn::make('barcode')
                    ->searchable(),
                TextColumn::make('aliquot'),
                TextColumn::make('priorSpecimenStatus'),
                IconColumn::make('received')
                    ->boolean(),
                TextColumn::make('receivedTime')
                    ->dateTime()
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
                // CreateAction::make(),
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->multiple()
                    ->using(function (BelongsToMany $relationship, array $data): void {
                        $recordIds = Arr::wrap($data['recordId']);
                        $specimens = Specimen::whereIn('id', $recordIds)->get()->keyBy('id');

                        $pivotData = [];
                        foreach ($recordIds as $id) {
                            $pivotData[$id] = ['priorSpecimenStatus' => $specimens[$id]->status];
                        }

                        $relationship->attach($pivotData);
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
                // EditAction::make(),
                DetachAction::make(),
                // DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                    // DeleteBulkAction::make(),
                ]),
            ]);
    }
}
