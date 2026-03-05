<?php

namespace App\Filament\Resources\Teams\Resources\Protocols\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class ProtocolForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->columns(4)
                    ->components([
                        TextInput::make('name')
                            ->autocomplete(false)
                            ->required()
                            ->columnSpan(3),
                        TextInput::make('version')
                            ->autocomplete(false)
                            ->required(),
                    ]),
                TextInput::make('uri')
                    ->required(),
                Textarea::make('description')
                    ->required(),
                Fieldset::make('Type')
                    ->schema([
                        TextInput::make('type')
                            ->label('Name')
                            ->required(),
                        TextInput::make('type_term_accession_number')
                            ->label('Term Accession Number')
                            ->requiredWith('type_term_reference'),
                        TextInput::make('type_term_reference')
                            ->label('Term Reference')
                            ->requiredWith('type_term_accession_number'),
                    ])
                    ->columns(3),
                Fieldset::make('Parameters')
                    ->schema([
                        TextInput::make('parameters_names')
                            ->required(),
                        TextInput::make('parameters_term_accession_number')
                            ->required(),
                        TextInput::make('parameters_term_reference')
                            ->required(),
                    ])
                    ->columns(3),
                TextInput::make('components_names')
                    ->required(),
                Fieldset::make('Components')
                    ->schema([
                        TextInput::make('components_type')
                            ->required(),
                        TextInput::make('components_type_term_accession_number')
                            ->required(),
                        TextInput::make('components_type_term_reference')
                            ->required(),
                    ])
                    ->columns(3),
            ])
            ->columns(1)
            ->extraAttributes(['class' => 'w-full xl:w-4/5 2xl:w-2/3']);
    }
}
