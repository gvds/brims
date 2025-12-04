<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('team_id')
                    ->relationship('team', 'name')
                    ->required(),
                Select::make('leader_id')
                    ->relationship('leader', 'id')
                    ->required(),
                TextInput::make('identifier')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                DatePicker::make('submission_date'),
                DatePicker::make('public_release_date'),
                TextInput::make('subjectID_prefix')
                    ->required(),
                TextInput::make('subjectID_digits')
                    ->required()
                    ->numeric(),
                TextInput::make('storageProjectName')
                    ->default(null),
                TextInput::make('last_subject_number')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
