<?php

namespace App\Filament\Project\Resources\Subjects\Schemas;

use App\Enums\SubjectStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class SubjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('subjectID')
                    ->required()
                    ->columnSpanFull(),
                Select::make('site_id')
                    ->relationship('site', 'name')
                    ->required(),
                Select::make('user_id')
                    ->label('Manager')
                    ->relationship(
                        name: 'user',
                        modifyQueryUsing: fn(Builder $query) => $query->whereAttachedTo(session()->get('currentProject'))
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn($record) => $record->fullname
                    )
                    ->required(),
                TextInput::make('firstname')
                    ->default(null),
                TextInput::make('lastname')
                    ->default(null),
                Repeater::make('address')
                    ->simple(
                        TextInput::make('addressEntry'),
                    ),
                //     ->columnSpanFull(),
                DatePicker::make('enrolDate'),
                Select::make('status')
                    ->options(SubjectStatus::class)
                    ->required(),
            ])
            ->columns(2)
            ->extraAttributes([
                'class' => 'w-1/3',
            ]);
    }
}
