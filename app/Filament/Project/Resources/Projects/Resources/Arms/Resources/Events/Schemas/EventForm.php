<?php

namespace App\Filament\project\Resources\Projects\Resources\Arms\Resources\Events\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('redcap_event_id')
                    ->numeric()
                    ->default(null),
                Toggle::make('autolog')
                    ->required(),
                TextInput::make('offset')
                    ->numeric()
                    ->default(null),
                TextInput::make('offset_ante_window')
                    ->numeric()
                    ->default(null),
                TextInput::make('offset_post_window')
                    ->numeric()
                    ->default(null),
                TextInput::make('name_labels')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('subject_event_labels')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('study_id_labels')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('event_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('repeatable')
                    ->required(),
                Toggle::make('active')
                    ->required(),
            ]);
    }
}
