<?php

namespace App\Filament\Resources\Projects\Resources\Subjects\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SubjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('subjectID')
                    ->searchable(),
                TextColumn::make('site_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('firstname')
                    ->searchable(),
                TextColumn::make('lastname')
                    ->searchable(),
                TextColumn::make('enrolDate')
                    ->date()
                    ->sortable(),
                TextColumn::make('arm_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('armBaselineDate')
                    ->date()
                    ->sortable(),
                TextColumn::make('previous_arm_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('previousArmBaselineDate')
                    ->date()
                    ->sortable(),
                TextColumn::make('subject_status')
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
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
