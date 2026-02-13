<?php

// namespace App\Filament\Resources\Projects\Schemas;

// use Filament\Forms\Components\DatePicker;
// use Filament\Forms\Components\Select;
// use Filament\Forms\Components\TextInput;
// use Filament\Forms\Components\Textarea;
// use Filament\Schemas\Schema;
// use Illuminate\Database\Eloquent\Builder;
// use Illuminate\Support\Facades\Auth;

// class ProjectForm
// {
//     public static function configure(Schema $schema): Schema
//     {
//         return $schema
//             ->components([
//                 Select::make('team_id')
//                     ->relationship('team', 'name')
//                     ->required(),
//                 Select::make('leader_id')
//                     ->relationship(
//                         name: 'leader',
//                         modifyQueryUsing: fn(Builder $query) => $query->where('team_id', Auth::user()->team_id)
//                     )
//                     ->getOptionLabelFromRecordUsing(
//                         fn($record) => $record->fullname
//                     )
//                     ->required(),
//                 TextInput::make('identifier')
//                     ->required(),
//                 TextInput::make('title')
//                     ->required(),
//                 Textarea::make('description')
//                     ->default(null)
//                     ->columnSpanFull(),
//                 DatePicker::make('submission_date'),
//                 DatePicker::make('public_release_date')
//                     ->visibleon(['view', 'edit']),
//                 TextInput::make('subjectID_prefix')
//                     ->label('Subject ID Prefix')
//                     ->required(),
//                 TextInput::make('subjectID_digits')
//                     ->label('Subject ID Digits')
//                     ->required()
//                     ->numeric(),
//                 TextInput::make('storageDesignation')
//                     ->default(null),
//                 TextInput::make('last_subject_number')
//                     ->required()
//                     ->numeric()
//                     ->default(0),
//             ]);
//     }
// }
