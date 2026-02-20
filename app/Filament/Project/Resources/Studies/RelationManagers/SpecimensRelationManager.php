<?php

namespace App\Filament\Project\Resources\Studies\RelationManagers;

use App\Filament\Exports\StudySpecimenExporter;
use App\Filament\Imports\StudySpecimenImporter;
use App\Models\Site;
use App\Models\Specimen;
use App\Models\Specimentype;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class SpecimensRelationManager extends RelationManager
{
    protected static string $relationship = 'specimens';

    #[\Override]
    public function isReadOnly(): bool
    {
        return false;
    }

    // public function form(Schema $schema): Schema
    // {
    //     return $schema
    //         ->components([
    //             TextInput::make('barcode')
    //                 ->required(),
    //             Select::make('subject_event_id')
    //                 ->relationship('subjectEvent', 'id')
    //                 ->required(),
    //             Select::make('specimenType_id')
    //                 ->relationship('specimenType', 'name')
    //                 ->required(),
    //             Select::make('site_id')
    //                 ->relationship('site', 'name')
    //                 ->required(),
    //             Select::make('project_id')
    //                 ->relationship('project', 'title')
    //                 ->required(),
    //             Select::make('status')
    //                 ->options(SpecimenStatus::class)
    //                 ->required()
    //                 ->default(0),
    //             Select::make('parentSpecimen_id')
    //                 ->relationship('parentSpecimen', 'id')
    //                 ->default(null),
    //             TextInput::make('aliquot')
    //                 ->required()
    //                 ->numeric(),
    //             TextInput::make('volume')
    //                 ->numeric()
    //                 ->default(null),
    //             TextInput::make('volumeUnit')
    //                 ->default(null),
    //             TextInput::make('thawcount')
    //                 ->required()
    //                 ->numeric()
    //                 ->default(0),
    //             Select::make('loggedBy_id')
    //                 ->relationship('loggedBy', 'id')
    //                 ->required(),
    //             DateTimePicker::make('loggedAt')
    //                 ->required(),
    //             Select::make('loggedOutBy_id')
    //                 ->relationship('loggedOutBy', 'id')
    //                 ->default(null),
    //             Select::make('usedBy_id')
    //                 ->relationship('usedBy', 'id')
    //                 ->default(null),
    //             DateTimePicker::make('usedAt'),
    //         ]);
    // }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('barcode')
            ->columns([
                TextColumn::make('barcode')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subjectEvent.event.name')
                    ->label(new HtmlString('Event : iteration'))
                    ->formatStateUsing(fn($state, $record) => new HtmlString("$state : {$record->subjectEvent->iteration}"))
                    ->searchable(),
                TextColumn::make('specimenType.name')
                    ->searchable(),
                TextColumn::make('site.name')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('aliquot')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('volume')
                    ->numeric()
                    ->formatStateUsing(fn($state, $record) => $state ? "$state{$record->volumeUnit}" : ''),
                TextColumn::make('loggedBy.username')
                    ->searchable(),
                TextColumn::make('loggedAt')
                    ->label('Logged Date')
                    ->date('Y-m-d'),
                TextColumn::make('usedBy.username')
                    ->searchable(),
                TextColumn::make('usedAt')
                    ->label('Used Date')
                    ->date('Y-m-d'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->schema([
                        Select::make('specimen_type_filter')
                            ->label('Filter by Specimen Type')
                            ->options(fn() => Specimentype::where('project_id', $this->ownerRecord->project_id)
                                ->pluck('name', 'id'))
                            ->searchable()
                            ->placeholder('All specimen types')
                            ->live(),
                        Select::make('site_filter')
                            ->label('Filter by Site')
                            ->options(fn() => Site::where('project_id', $this->ownerRecord->project_id)
                                ->pluck('name', 'id'))
                            ->searchable()
                            ->placeholder('All sites')
                            ->live(),
                        Select::make('recordId')
                            ->label('Specimens')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->options(function (Get $get) {
                                $query = Specimen::query()
                                    ->where('project_id', $this->ownerRecord->project_id)
                                    ->whereNotIn('id', $this->ownerRecord->specimens()->pluck('specimens.id'));

                                if ($specimenTypeId = $get('specimen_type_filter')) {
                                    $query->where('specimenType_id', $specimenTypeId);
                                }

                                if ($siteId = $get('site_filter')) {
                                    $query->where('site_id', $siteId);
                                }

                                return $query->pluck('barcode', 'id');
                            })
                            ->required(),
                    ])
                    ->multiple()
                    ->hidden(fn(): bool => $this->ownerRecord->locked)
                    ->attachAnother(false),
                ImportAction::make()
                    ->importer(StudySpecimenImporter::class)
                    ->options(function (): array {
                        return [
                            'study' => $this->ownerRecord,
                            'project' => $this->ownerRecord->project,
                        ];
                    })
                    ->hidden(fn(): bool => $this->ownerRecord->locked)
                    ->color(Color::Indigo),
                ExportAction::make()
                    ->label('Export Specimens')
                    ->exporter(StudySpecimenExporter::class)
                    ->color(Color::Indigo),
            ])
            ->recordActions([
                // EditAction::make(),
                DetachAction::make()
                    ->hidden(fn(): bool => $this->ownerRecord->locked),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ])
                    ->hidden(fn(): bool => $this->ownerRecord->locked),
            ]);
    }
}
