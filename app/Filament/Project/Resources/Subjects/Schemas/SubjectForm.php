<?php

namespace App\Filament\Project\Resources\Subjects\Schemas;

use App\Enums\SubjectStatus;
use Filament\Forms\Components\DatePicker;
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
                TextInput::make('project_id')
                    ->required()
                    ->numeric(),
                Select::make('site_id')
                    ->relationship('site', 'name')
                    ->required(),
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('firstname')
                    ->default(null),
                TextInput::make('lastname')
                    ->default(null),
                Textarea::make('address')
                    ->default(null)
                    ->columnSpanFull(),
                DatePicker::make('enrolDate'),
                Select::make('arm_id')
                    ->relationship('arm', 'name')
                    ->default(null),
                DatePicker::make('armBaselineDate'),
                TextInput::make('previous_arm_id')
                    ->numeric()
                    ->default(null),
                DatePicker::make('previousArmBaselineDate'),
                Select::make('subject_status')
                    ->options(SubjectStatus::class)
                    ->required(),
            ]);
    }
}
