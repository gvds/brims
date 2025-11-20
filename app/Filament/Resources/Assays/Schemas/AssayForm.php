<?php

namespace App\Filament\Resources\Assays\Schemas;

use App\Models\AssayDefinition;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;

class AssayForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(100),
                Select::make('assaydefinition_id')
                    ->relationship('assaydefinition', 'name')
                    ->label('Assay Definition')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set): void {
                        $set('additional_fields', []);
                    }),
                TextInput::make('technologyPlatform')
                    ->required()
                    ->maxLength(50),
                // FileUpload::make('assayfile')
                //     ->label('Assay File')
                //     ->disk('assayfiles')
                //     ->storeFileNamesIn('assayfilename'),
                TextInput::make('uri')
                    ->label('URI'),
                TextInput::make('location'),
                Group::make()
                    ->schema(function (callable $get) {
                        $assayDefinitionId = $get('assaydefinition_id');
                        if (!$assayDefinitionId) {
                            return [];
                        }
                        $definition = AssayDefinition::find($assayDefinitionId);
                        if (!$definition || !$definition->additional_fields) {
                            return [];
                        }
                        $fields = [];
                        foreach ($definition->additional_fields as $field) {
                            $fieldname = 'additional_fields.' . $field['field_name'];
                            if ($field['field_type'] === 'text') {
                                $fields[] = TextInput::make($fieldname)
                                    ->label($field['label'] ?? null)
                                    ->required($field['required'] ?? false)
                                    ->numeric(function () use ($field) {
                                        if (in_array($field['sub_type'], ['numberic', 'integer'])) {
                                            return $field['max_length'] ?? 255;
                                        }
                                        return false;
                                    })
                                    ->maxLength(function () use ($field) {
                                        if (!in_array($field['sub_type'], ['numberic', 'integer'])) {
                                            return $field['max_length'] ?? 255;
                                        }
                                        return null;
                                    });
                            }
                            if ($field['field_type'] === 'date') {
                                $fields[] = DatePicker::make($fieldname)
                                    ->label($field['label'] ?? null)
                                    ->required($field['required'] ?? false);
                            }
                            if ($field['field_type'] === 'select') {
                                $fields[] = Select::make($fieldname)
                                    ->label($field['label'] ?? null)
                                    ->options(fn() => collect($field['field_options'] ?? [])
                                        ->mapWithKeys(fn($option): array => [$option['option_value'] => $option['option_label']]))
                                    ->required($field['required'] ?? false);
                            }
                            if ($field['field_type'] === 'radio') {
                                $fields[] = Fieldset::make()
                                    ->schema([
                                        Radio::make($fieldname)
                                            ->label($field['label'] ?? null)
                                            ->options(fn() => collect($field['field_options'] ?? [])
                                                ->mapWithKeys(fn($option): array => [$option['option_value'] => $option['option_label']]))
                                            ->inline()
                                            ->required($field['required'] ?? false)
                                    ]);
                            }
                            if ($field['field_type'] === 'checkboxlist') {
                                $fields[] = Fieldset::make()
                                    ->schema([
                                        CheckboxList::make($fieldname)
                                            ->label($field['label'] ?? null)
                                            ->options(fn() => collect($field['field_options'] ?? [])
                                                ->mapWithKeys(fn($option): array => [$option['option_value'] => $option['option_label']]))
                                            ->required($field['required'] ?? false)
                                    ]);
                            }
                            if ($field['field_type'] === 'checkbox') {
                                $fields[] = Fieldset::make()
                                    ->schema([
                                        Checkbox::make($fieldname)
                                            ->label($field['label'] ?? null)
                                            ->required($field['required'] ?? false)
                                    ]);
                            }
                            // Add more field types as needed
                        }
                        return $fields;
                    })
                    ->columns(1),
            ])
            ->columns(1)
            ->extraAttributes(['class' => 'mb-5']);
    }
}
