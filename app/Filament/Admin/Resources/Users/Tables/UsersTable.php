<?php

namespace App\Filament\Admin\Resources\Users\Tables;

use App\Enums\SystemRoles;
use App\Models\Team;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use STS\FilamentImpersonate\Actions\Impersonate;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar_url')
                    ->circular()
                    ->imageSize(40)
                    ->state(fn(User $record): ?string => $record->avatar_url ? asset('storage/' . $record->avatar_url) : null),
                TextColumn::make('username')
                    ->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('fullname')
                    ->label('Name')
                    ->searchable(['firstname', 'lastname'], isIndividual: true, isGlobal: false),
                TextColumn::make('team.name')
                    ->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('team_role')
                    ->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('telephone')
                    ->prefix('+'),
                // ->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('system_role')
                    ->label('System Role')
                    ->searchable(isIndividual: true, isGlobal: false),
                IconColumn::make('active')
                    ->boolean(),
                TextColumn::make('last_login')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
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
                // SelectFilter::make('institution_id')
                //     ->relationship('institution', 'name')
                //     ->multiple()
                //     ->searchable()
                //     ->preload()
                //     ->label('Institution')
                //     ->placeholder('All Institutions')
                //     ->default(null),
                SelectFilter::make('team_id')
                    ->label('Team')
                    ->options(fn() => Team::pluck('name', 'id'))
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->placeholder('All Teams')
                    ->default(null),
            ])
            ->recordActions([
                EditAction::make(),
                // ->hidden(fn(User $record) => $record->system_role === SystemRoles::SuperAdmin && Auth::user()->system_role !== SystemRoles::SuperAdmin),
                Impersonate::make()
                    ->hidden(fn(User $record): bool => $record->system_role === SystemRoles::SuperAdmin),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
