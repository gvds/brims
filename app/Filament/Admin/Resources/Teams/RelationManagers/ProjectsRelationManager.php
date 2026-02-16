<?php

namespace App\Filament\Admin\Resources\Teams\RelationManagers;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ProjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'projects';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        // return ProjectForm::configure($schema)->extraAttributes(['class' => 'w-full']);
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('identifier')
                    ->required()
                    ->unique(ignoreRecord: true),
                Select::make('studydesign_id')
                    ->relationship(name: 'studydesign', titleAttribute: 'type')
                    ->required(),
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
                Fieldset::make("Subject ID")
                    ->contained(false)
                    ->schema([
                        TextInput::make('subjectID_prefix')
                            ->label('Prefix')
                            ->hint('Between 2 and 10 uppercase characters')
                            ->required()
                            ->maxLength(10)
                            ->minLength(2)
                            ->regex('/^[A-Z]{2,10}$/'),
                        TextInput::make('subjectID_digits')
                            ->label('Digits')
                            ->numeric()
                            ->required()
                            ->minValue(2)
                            ->maxValue(8)
                            ->hint('The number of digits in a subject ID'),
                    ]),
                TextInput::make('storageDesignation')
                    ->label('Storage Designation')
                    ->required()
                    ->maxLength(40),
                Grid::make(2)
                    ->schema([
                        DatePicker::make('submission_date'),
                        DatePicker::make('public_release_date')
                            ->visibleOn(['view', 'edit']),
                    ]),
            ])
            ->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            // ->striped()
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('studydesign.type')
                    ->label('Study Design')
                    ->searchable(),
                TextColumn::make('leader.fullname')
                    ->searchable(['firstname', 'lastname']),
                TextColumn::make('submission_date')
                    ->date('Y-m-d')
                    ->sortable(),
                TextColumn::make('public_release_date')
                    ->date('Y-m-d')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('title')
            ->filters([
                //
            ])
            ->recordUrl(
                null
            )
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
