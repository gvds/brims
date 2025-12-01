<?php

namespace App\Filament\Resources\Assays\Schemas;

use App\Models\AssayDefinition;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class AssayForm
{
    public static function configure(Schema $schema, $cols = 1): Schema
    {

        $components = function (callable $get) {
            $fields = [
                TextInput::make('name')
                    ->required()
                    ->live()
                    ->maxLength(100),
                Select::make('assaydefinition_id')
                    ->relationship('assaydefinition', 'name')
                    ->label('Assay Definition')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set): void {
                        $set('additional_fields', []);
                    }),
                TextInput::make('technologyPlatform')
                    ->required()
                    ->live()
                    ->maxLength(50),
                TextInput::make('uri')
                    ->label('URI'),
                TextInput::make('location'),
            ];
            $assayDefinitionId = $get('assaydefinition_id');
            if (!$assayDefinitionId) {
                return $fields;
            }
            $definition = AssayDefinition::find($assayDefinitionId);
            if (!$definition || !$definition->additional_fields) {
                return $fields;
            }
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
                    $fields[] = Radio::make($fieldname)
                        // ->label($field['label'] ?? null)
                        ->options(fn() => collect($field['field_options'] ?? [])
                            ->mapWithKeys(fn($option): array => [$option['option_value'] => $option['option_label']]))
                        ->inline()
                        // ->extraInputAttributes(['class' => 'text-sm bg-emerald-600'])
                        ->extraAttributes(
                            fn(string $operation) => $operation === 'view' ?
                                ['class' => 'border border-stone-200 dark:border-zinc-700 bg-stone-50 dark:bg-zinc-900 shadow-xs rounded-lg p-2'] :
                                ['class' => 'border border-stone-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800 p-2']
                        )
                        ->required($field['required'] ?? false);
                }
                if ($field['field_type'] === 'checkboxlist') {
                    $fields[] = CheckboxList::make($fieldname)
                        // ->label($field['label'] ?? null)
                        ->options(fn() => collect($field['field_options'] ?? [])
                            ->mapWithKeys(fn($option): array => [$option['option_value'] => $option['option_label']]))
                        ->extraAttributes(
                            fn(string $operation) => $operation === 'view' ?
                                ['class' => 'mt-1 border border-stone-200 dark:border-zinc-700 rounded-lg bg-stone-50 dark:bg-zinc-900 shadow-xs px-3 pb-2'] :
                                ['class' => 'mt-1 border border-stone-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800 px-3 pb-2']
                        )
                        ->columns(2)
                        ->required($field['required'] ?? false);
                }
                if ($field['field_type'] === 'checkbox') {
                    $fields[] = Checkbox::make($fieldname)
                        ->label($field['label'] ?? null)
                        ->extraAttributes(
                            fn(string $operation) => $operation === 'view' ?
                                ['class' => 'border border-stone-200 dark:border-zinc-700 bg-stone-50 dark:bg-zinc-900 shadow-xs rounded-lg p-2'] :
                                ['class' => 'border border-stone-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800 p-2']
                        )
                        ->required($field['required'] ?? false);
                }
            }
            return $fields;
        };

        return $schema
            ->components([
                Grid::make($cols)
                    ->schema($components)
            ])
            ->columns(1)
            ->extraAttributes(['class' => 'mb-5']);
    }
}
