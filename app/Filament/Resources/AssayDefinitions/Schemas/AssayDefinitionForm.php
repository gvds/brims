<?php

namespace App\Filament\Resources\AssayDefinitions\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class AssayDefinitionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(50),
                Textarea::make('description')
                    ->required()
                    ->rows(3)
                    ->maxLength(500),
                // TextInput::make('platform')
                //     ->required()
                //     ->maxLength(200),
                Fieldset::make('Measurement Type')
                    ->schema([
                        TextInput::make('measurementType')
                            ->label('Type')
                            ->required()
                            ->maxLength(50),
                        TextInput::make('measurementTypeTermSourceReference')
                            ->label('Source Reference')
                            ->maxLength(50)
                            ->default(null),
                        TextInput::make('measurementTypeTermAccessionNumber')
                            ->label('Term Accession Number')
                            ->maxLength(50)
                            ->default(null),
                    ])
                    ->columns(3),
                Fieldset::make('Technology Type')
                    ->schema([
                        TextInput::make('technologyType')
                            ->label('Type')
                            ->required()
                            ->maxLength(50),
                        TextInput::make('technologyTypeTermSourceReference')
                            ->label('Source Reference')
                            ->maxLength(50)
                            ->default(null),
                        TextInput::make('technologyTypeTermAccessionNumber')
                            ->label('Term Accession Number')
                            ->maxLength(50)
                            ->default(null),
                    ])->columns(3),
                Repeater::make('additional_fields')
                    ->schema([
                        // Grid::make(1)
                        //     ->schema([
                        TextInput::make('field_name')
                            ->label('Field Name')
                            ->required()
                            ->maxLength(50),
                        Select::make('field_type')
                            ->label('Field Type')
                            ->live()
                            ->options([
                                'text' => 'Text',
                                'select' => 'Select',
                                'radio' => 'Radio',
                                'checkbox' => 'Checkbox',
                                'checkboxlist' => 'Checkbox List',
                                'date' => 'Date',
                            ])
                            ->required(),
                        // ]),
                        TextInput::make('max_length')
                            ->label('Max Length')
                            ->integer()
                            ->minValue(1)
                            ->maxValue(255)
                            ->default(255)
                            ->visible(fn(Get $get) => in_array($get('field_type'), ['text'])),
                        Select::make('sub_type')
                            ->label('Sub Type')
                            ->live()
                            ->options([
                                'text' => 'Text',
                                'integer' => 'Integer',
                                'numeric' => 'Numeric',
                            ])
                            ->default('text')
                            ->afterStateUpdated(function ($state, callable $set) {
                                foreach (['min_value', 'max_value'] as $field) {
                                    $set($field, null);
                                }
                            })
                            ->visible(fn(Get $get) => in_array($get('field_type'), ['text'])),
                        TextInput::make('min_value')
                            ->label('Min Value')
                            ->integer()
                            ->visible(fn(Get $get) => in_array($get('sub_type'), ['integer', 'numeric'])),
                        TextInput::make('max_value')
                            ->label('Max Value')
                            ->integer()
                            ->visible(fn(Get $get) => in_array($get('sub_type'), ['integer', 'numeric'])),
                        Repeater::make('field_options')
                            ->label('Field Options')
                            ->table([
                                TableColumn::make('Value'),
                                TableColumn::make('Label'),
                            ])
                            ->schema([
                                TextInput::make('option_value')
                                    ->label('Option Value')
                                    ->required()
                                    ->maxLength(50)
                                    ->distinct(),
                                TextInput::make('option_label')
                                    ->label('Option Label')
                                    ->required()
                                    ->maxLength(50),
                            ])
                            ->columns(2)
                            ->columnSpanFull()
                            ->defaultItems(1)
                            ->visible(fn(Get $get) => in_array($get('field_type'), ['select', 'radio', 'checkboxlist'])),
                        Toggle::make('required')
                            ->label('Required')
                            ->default(false)
                            ->inline(false),

                    ])
                    ->columns(2),
                Toggle::make('active')
                    ->default(true)
                    ->inline(false)
                    ->required(),
            ])
            ->columns(1)
            ->extraAttributes(['class' => 'w-1/2']);
    }
}
