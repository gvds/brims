<?php

namespace App\Filament\Resources\Projects\Resources\Studies\RelationManagers;

use App\Filament\Resources\Assays\AssayResource;
use App\Filament\Resources\Assays\Schemas\AssayForm;
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

    protected static ?string $relatedResource = AssayResource::class;

    // protected string $view = 'filament.resources.assays.pages.create-assay';

    public $infos = [];

    #[\Override]
    public function isReadOnly(): bool
    {
        return false;
    }

    #[\Override]
    public function form(Schema $schema): Schema
    {
        return AssayForm::configure($schema);
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
                // ->schema([
                //     TextInput::make('title')
                //         ->required()
                //         ->maxLength(255),
                //     // ...
                // ]),
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
