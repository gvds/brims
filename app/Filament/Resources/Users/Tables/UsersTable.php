<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use STS\FilamentImpersonate\Actions\Impersonate;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('username')
                    ->searchable(),
                TextColumn::make('fullname')
                    ->label('Name')
                    ->searchable(['firstname', 'lastname']),
                TextColumn::make('team.name'),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('telephone')
                    ->prefix('+')
                    ->searchable(),
                TextColumn::make('homesite')
                    ->label('Home Site'),
                TextColumn::make('roles.name')
                    ->label('Role')
                    ->formatStateUsing(fn(string $state): string => Str::title(Str::replace('_', ' ', $state))),
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
                Filter::make('active')
                    ->query(fn($query) => $query->where('active', true))
                    ->label('Active')
                    ->toggle()
                    ->default(),
                SelectFilter::make('homesite')
                    ->options(fn() => \App\Models\User::whereNotNull('homesite')->distinct('homesite')->pluck('homesite', 'homesite'))
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->label('Home Site')
                    ->placeholder('All Home Sites')
                    ->default(null),
                SelectFilter::make('team_id')
                    ->label('Team')
                    ->options(fn() => \App\Models\Team::pluck('name', 'id'))
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->placeholder('All Teams')
                    ->default(null),
            ])
            ->deferFilters(false)
            ->recordActions([
                EditAction::make(),
                Impersonate::make()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
