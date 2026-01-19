<?php

namespace App\Filament\Resources\Teams\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class TeamForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->autocomplete(false)
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->default(null),
                Select::make('leader_id')
                    ->relationship(
                        name: 'leader',
                        modifyQueryUsing: fn($query, Model $record) => $query->where('team_id', $record->id)->where('team_role', 'Admin')
                    )
                    ->getOptionLabelFromRecordUsing(fn(Model $record) => $record->fullname)
                    ->searchable(['firstname', 'lastname'])
                    ->preload()
                    ->required()
                    ->visibleOn(['edit']),
            ])
            ->columns(1)
            ->extraAttributes(['class' => 'w-1/3']);
    }
}
