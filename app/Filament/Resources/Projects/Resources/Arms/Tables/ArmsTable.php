<?php

namespace App\Filament\Resources\Projects\Resources\Arms\Tables;

use App\Models\Arm;
use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\TextSize;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ArmsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('arm_num')
            ->defaultSort('arm_num', 'asc')
            ->columns([
                // TextColumn::make('id'),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('arm_num')
                    ->label('Arm Number')
                    ->alignCenter()
                    ->numeric(),
                IconColumn::make('manual_enrol')
                    ->label('Manual Enrolment')
                    ->alignCenter()
                    ->boolean(),
                TextColumn::make('switcharms')
                    ->label('Switchable Arms')
                    ->state(fn($record) => Arm::whereIn('id', $record->switcharms ?? [])->get()->map(fn($arm): string => $arm['arm_num'] . ': ' . $arm['name']))
                    ->listWithLineBreaks()
                    ->size(TextSize::ExtraSmall)
                    ->placeholder('--- No Switchable Arms ---'),
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
            ->recordActions([
                ViewAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
