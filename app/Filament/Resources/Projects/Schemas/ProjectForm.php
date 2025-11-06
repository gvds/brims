<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Filament\Resources\Projects\Resources\ImportValueMappings\Schemas\ImportValueMappingForm;
use App\Models\Site;
use Dom\Text;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ProjectForm
{

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Project Details')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('identifier')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Textarea::make('description')
                            ->default(null)
                            ->columnSpanFull(),
                        Select::make('leader_id')
                            ->relationship(
                                name: 'leader',
                                modifyQueryUsing: fn(Builder $query) => $query->where('team_id', Auth::user()->team_id)
                            )
                            ->getOptionLabelFromRecordUsing(
                                fn($record) => $record->fullname
                            )
                            ->required(),
                        Fieldset::make("Subject ID")
                            ->contained(false)
                            ->schema([
                                TextInput::make('subjectID_prefix')
                                    ->label('Prefix')
                                    ->hint('Between 2 and 10 uppercase characters')
                                    ->required()
                                    ->maxLength(10)
                                    ->minLength(2)
                                    ->regex('/^[A-Z]{2,10}$/'),
                                TextInput::make('subjectID_digits')
                                    ->label('Digits')
                                    ->numeric()
                                    ->required()
                                    ->minValue(2)
                                    ->maxValue(8)
                                    ->hint('The number of digits in a subject ID'),
                            ]),
                        TextInput::make('storageProjectName')
                            ->label('Storage Project Name')
                            ->required()
                            ->maxLength(40),
                        Grid::make(2)
                            ->schema([
                                DatePicker::make('submission_date'),
                                DatePicker::make('public_release_date')
                                    ->visibleOn('edit')
                            ]),
                    ]),
            ])
            ->columns(1)
            ->extraAttributes([
                'class' => 'w-1/3',
            ]);
    }
}
