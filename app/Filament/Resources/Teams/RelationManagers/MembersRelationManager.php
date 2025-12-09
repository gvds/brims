<?php

namespace App\Filament\Resources\Teams\RelationManagers;

use App\Enums\TeamRoles;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MembersRelationManager extends RelationManager
{
    protected static string $relationship = 'members';

    public function isReadOnly(): bool
    {
        return false; // This allows editing actions
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('username')
                    ->required()
                    ->maxLength(20)
                    ->unique(ignoreRecord: true)
                    ->regex('/^[a-z][a-z0-9.]+$/')
                    ->validationMessages([
                        'regex' => 'The :attribute may contain only lower-case letters, periods and numbers and must start with a letter.',
                    ]),
                TextInput::make('firstname')
                    ->required()
                    ->maxLength(50),
                TextInput::make('lastname')
                    ->required()
                    ->maxLength(50),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Select::make('team_role')
                    ->options(fn(): array|string => $this->ownerRecord->members->count() === 0 ? TeamRoles::admin() : TeamRoles::class)
                    ->required(),
                TextInput::make('telephone')
                    ->prefix('+')
                    ->mask('99 (99) 999-9999')
                    ->maxLength(20)
                    ->default(null),
                TextInput::make('homesite')
                    ->label('Home Site')
                    ->maxLength(10)
                    ->default(null),
                Toggle::make('active')
                    ->visibleOn('edit'),
            ]);
    }

    public function table(Table $table): Table
    {
        // return UsersTable::configure($table);
        return $table
            ->columns([
                TextColumn::make('username')
                    ->searchable(),
                TextColumn::make('fullname')
                    ->label('Name')
                    ->searchable(['firstname', 'lastname']),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('telephone')
                    ->prefix('+')
                    ->searchable(),
                TextColumn::make('homesite')
                    ->label('Home Site'),
                TextColumn::make('team_role')
                    ->label('Role'),
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
                    ->toggle(),
                SelectFilter::make('homesite')
                    ->options(fn() => \App\Models\User::distinct('homesite')->pluck('homesite', 'homesite'))
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->label('Home Site')
                    ->placeholder('All Home Sites')
                    ->default(null),
            ])
            ->deferFilters(false)
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                CreateAction::make(),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
