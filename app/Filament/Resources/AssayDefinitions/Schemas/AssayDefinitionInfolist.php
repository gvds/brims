<?php

namespace App\Filament\Resources\AssayDefinitions\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class AssayDefinitionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->schema([
                        TextEntry::make('name'),
                        IconEntry::make('active')
                            ->boolean(),
                    ])
                    ->columns(2),
                TextEntry::make('description'),
                Fieldset::make('Measurement Type')
                    ->schema([
                        TextEntry::make('measurementType')
                            ->label('Type'),
                        TextEntry::make('measurementTypeTermSourceReference')
                            ->label('Source Reference'),
                        TextEntry::make('measurementTypeTermAccessionNumber')
                            ->label('Term Accession Number'),
                    ])
                    ->columns(3),
                Fieldset::make('Technology Type')
                    ->schema([
                        TextEntry::make('technologyType')
                            ->label('Type'),
                        TextEntry::make('technologyTypeTermSourceReference')
                            ->label('Source Reference'),
                        TextEntry::make('technologyTypeTermAccessionNumber')
                            ->label('Term Accession Number'),
                    ])->columns(3),
                RepeatableEntry::make('additional_fields')
                    ->schema([
                        TextEntry::make('field_name')
                            ->label('Field Name'),
                        TextEntry::make('field_type')
                            ->label('Field Type'),
                        TextEntry::make('max_length')
                            ->label('Max Length')
                            ->visible(fn(Get $get): bool => in_array($get('field_type'), ['text'])),
                        TextEntry::make('sub_type')
                            ->label('Sub Type')
                            ->visible(fn(Get $get): bool => in_array($get('field_type'), ['text'])),
                        TextEntry::make('min_value')
                            ->label('Min Value')
                            ->visible(fn(Get $get): bool => in_array($get('sub_type'), ['integer', 'numeric'])),
                        TextEntry::make('max_value')
                            ->label('Max Value')
                            ->visible(fn(Get $get): bool => in_array($get('sub_type'), ['integer', 'numeric'])),
                        RepeatableEntry::make('field_options')
                            ->label('Field Options')
                            ->table([
                                TableColumn::make('Value'),
                                TableColumn::make('Label'),
                            ])
                            ->schema([
                                TextEntry::make('option_value')
                                    ->label('Option Value'),
                                TextEntry::make('option_label')
                                    ->label('Option Label'),
                            ])
                            ->columns(2)
                            ->columnSpanFull()
                            ->visible(fn(Get $get): bool => in_array($get('field_type'), ['select', 'radio', 'checkboxlist'])),
                        IconEntry::make('required')
                            ->label('Required')
                            ->boolean(),

                    ])
                    ->columns(2),
            ])
            ->columns(1)
            ->extraAttributes(['class' => 'w-1/2']);
    }
}
