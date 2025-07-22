<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Models\Site;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
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
                Grid::make(2)
                    ->schema([
                        DatePicker::make('submission_date'),
                        DatePicker::make('public_release_date')
                            ->visibleOn('edit')
                    ]),
            ])
            ->columns(1)
            ->extraAttributes([
                'class' => 'w-1/3',
            ]);
    }
}
