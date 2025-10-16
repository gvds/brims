<?php

namespace App\Filament\Resources\Projects\Resources\Studies\RelationManagers;

use App\Models\AssayDefinition;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AssaysRelationManager extends RelationManager
{
    protected static string $relationship = 'assays';

    #[\Override]
    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        // return StudyForm::configure($schema);
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
                FileUpload::make('assayfile')
                    ->label('Assay File')
                    ->disk('assayfiles')
                    ->storeFileNamesIn('assayfilename'),
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
            ->columns(1);
    }

    public function table(Table $table): Table
    {
        // return StudiesTable::configure($table);
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('study.title')
                    ->sortable(),
                TextColumn::make('assaydefinition.name')
                    ->sortable(),
                TextColumn::make('technologyPlatform')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->using(function (array $data, string $model): Model {
                        $data['study_id'] = $this->getOwnerRecord()->id;
                        $data['user_id'] = auth()->id();
                        return $model::create($data);
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->using(function (Model $record, array $data): Model {
                        if (isset($record->assayfile) && $data['assayfile'] != $record->assayfile) {
                            Storage::disk('assayfiles')->delete($record->assayfile);
                        }
                        $record->update($data);

                        return $record;
                    }),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
