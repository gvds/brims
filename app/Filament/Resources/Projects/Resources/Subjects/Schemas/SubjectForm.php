<?php

namespace App\Filament\Resources\Projects\Resources\Subjects\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SubjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('subjectID')
                    ->required(),
                Select::make('site_id')
                    ->relationship('site', 'name')
                    ->required(),
                TextInput::make('firstname')
                    ->default(null),
                TextInput::make('lastname')
                    ->default(null),
                Repeater::make('address')
                    ->schema([
                        TextInput::make('street')
                            ->required()
                    ])
                    ->columnSpanFull(),
                DatePicker::make('enrolDate'),
                Select::make('arm_id')
                    ->relationship('arm', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                DatePicker::make('armBaselineDate'),
                TextInput::make('subject_status')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
