<?php

namespace App\Filament\Project\Resources\Subjects\Tables;

use App\Enums\SubjectStatus;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SubjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('subjectID')
                    ->label('Subject ID')
                    ->searchable(),
                TextColumn::make('site.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('user.fullname')
                    ->label('Manager')
                    ->searchable(),
                TextColumn::make('firstname')
                    ->searchable(),
                TextColumn::make('lastname')
                    ->searchable(),
                TextColumn::make('enrolDate')
                    ->date('Y-m-d')
                    ->sortable(),
                TextColumn::make('arm.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('armBaselineDate')
                    ->date('Y-m-d')
                    ->sortable(),
                TextColumn::make('previousArm.name'),
                TextColumn::make('previousArmBaselineDate')
                    ->label('Previous Arm Baseline Date')
                    ->date('Y-m-d')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
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
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(SubjectStatus::class),
                SelectFilter::make('site_id')
                    ->relationship('site', 'name')
                    ->label('Site')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('user_id')
                    ->options(fn(): array => User::all()->pluck('fullname', 'id')->toArray())
                    // ->relationship(
                    //     name: 'user',
                    //     titleAttribute: fn($record) => dd($record)
                    //     // titleAttribute: fn($record) => $record->user->firstname . ' ' . $record->user->lastname
                    // )
                    // ->attribute('fullname')
                    ->label('Manager')
                    ->searchable()
                    ->preload(),
            ])
            ->deferFilters(false)
            ->recordActions([
                ViewAction::make()
                    ->visible(fn($record) => $record->status !== SubjectStatus::Generated),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
