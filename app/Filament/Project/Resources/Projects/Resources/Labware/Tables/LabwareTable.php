<?php

namespace App\Filament\project\Resources\Projects\Resources\Labware\Tables;

use App\Models\Labware;
use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class LabwareTable
{

    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('barcodeFormat'),
                TextColumn::make('specimenTypes.name'),
                TextColumn::make('specimenTypes_count')
                    ->label('Specimen Types')
                    ->counts('specimenTypes'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordClasses(fn(Model $record): string => isset($record->project_id) ? 'text-gray-500' : 'text-green-500')
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateDataUsing(function (array $data): array {
                        self::regexPreAndPostfix($data);
                        return $data;
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->mutateDataUsing(function (array $data): array {
                        self::regexPreAndPostfix($data);
                        return $data;
                    }),
                DeleteAction::make()
                    ->hidden(fn(Labware $record): bool => $record->specimenTypes->count() > 0)
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    private static function regexPreAndPostfix(array &$data): void
    {
        if ($data['barcodeFormat'][0] !== '^') {
            $data['barcodeFormat'] = '^' . $data['barcodeFormat'];
        }
        if ($data['barcodeFormat'][-1] !== '$') {
            $data['barcodeFormat'] .= '$';
        }
    }
}
