<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\TeamRoles;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make([
                    'default' => 1,
                    'sm' => 2
                ])
                    ->schema([
                        TextInput::make('username')
                            ->required()
                            ->maxLength(20)
                            ->unique(ignoreRecord: true)
                            ->regex('/^[a-z][a-z0-9.]+$/')
                            ->validationMessages([
                                'regex' => 'The :attribute may contain only lower-case letters, periods and numbers and must start with a letter.',
                            ]),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                    ]),
                Grid::make([
                    'default' => 1,
                    'sm' => 2
                ])
                    ->schema([
                        TextInput::make('firstname')
                            ->label('First Name')
                            ->required()
                            ->maxLength(50),
                        TextInput::make('lastname')
                            ->label('Last Name')
                            ->required()
                            ->maxLength(50),
                    ]),
                Grid::make([
                    'default' => 1,
                    'sm' => 2
                ])
                    ->schema([
                        TextInput::make('telephone')
                            // ->tel()
                            ->prefix('+')
                            ->mask('99 (99) 999-9999')
                            ->maxLength(20)
                            ->default(null),
                        TextInput::make('homesite')
                            ->label('Home Site')
                            ->maxLength(10)
                            ->required()
                            ->default(null),
                    ]),
                Grid::make([
                    'default' => 1,
                    'sm' => 2
                ])
                    ->schema([
                        Select::make('team_id')
                            ->label('Team')
                            ->relationship('team')
                            ->getOptionLabelFromRecordUsing(fn($record) => $record->name)
                            ->requiredWith('team_role')
                            ->searchable()
                            ->preload()
                            ->default(null),
                        Select::make('team_role')
                            ->options(TeamRoles::class)
                            ->requiredWith('team_id'),
                    ]),
                Grid::make([
                    'default' => 1,
                    'sm' => 2
                ])
                    ->schema([
                        Select::make('roles')
                            ->label('Role')
                            ->relationship(
                                titleAttribute: 'name',
                                modifyQueryUsing: fn($query) => auth()->user()->hasRole('super_admin') ? $query : $query->whereNot('name', 'super_admin')
                            )
                            ->getOptionLabelFromRecordUsing(fn(Role $role) => Str::title(Str::replace('_', ' ', $role->name)))
                            ->required()
                            ->default(4),
                        Toggle::make('active')
                            ->required()
                            ->default(true)
                            ->inline(false),
                    ]),
            ])
            ->columns(1)
            ->extraAttributes(['class' => 'w-1/3']);
    }
}
