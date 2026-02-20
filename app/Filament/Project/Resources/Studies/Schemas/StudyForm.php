<?php

namespace App\Filament\Project\Resources\Studies\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class StudyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->autocomplete(false)
                    ->required(),
                TextInput::make('identifier')
                    ->autocomplete(false)
                    ->required(),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                DatePicker::make('submission_date'),
                DatePicker::make('public_release_date'),
                // TextInput::make('studyfile')
                //     ->default(null),
                // TextInput::make('studyfilename')
                //     ->default(null),
                Toggle::make('locked')
                    ->helperText('When locked, the assay cannot be edited and specimens cannot be added or deleted.'),
            ])
            ->columns([
                'xs' => 1,
                'sm' => 2,
            ])
            ->extraAttributes(['class' => 'w-full lg:w-2/3 xl:w-1/2']);
    }
}
