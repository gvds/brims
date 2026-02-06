<?php

namespace App\Filament\Project\Resources\Manifests\RelationManagers;

use App\Enums\ManifestStatus;
use App\Enums\SpecimenStatus;
use App\Filament\Imports\ManifestItemImporter;
use App\Models\Specimen;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\ImportAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class SpecimensRelationManager extends RelationManager
{
    protected static string $relationship = 'specimens';

    public function isReadOnly(): bool
    {
        return false;
    }

    protected $listeners = ['refreshSpecimensRelation' => '$refresh'];

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('barcode')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('barcode'),
                TextEntry::make('aliquot'),
                TextEntry::make('thawcount'),
                TextEntry::make('volume')
                    ->formatStateUsing(fn(Specimen $record): string => "{$record->volume}{$record->volumeUnit}"),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('barcode')
            ->columns([
                TextColumn::make('barcode')
                    ->searchable(),
                TextColumn::make('aliquot'),
                TextColumn::make('priorSpecimenStatus'),
                IconColumn::make('received')
                    ->boolean(),
                TextColumn::make('receivedTime')
                    ->dateTime()
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
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Select Specimens to Add')
                    ->preloadRecordSelect()
                    ->multiple()
                    ->recordSelectOptionsQuery(
                        fn($query) => $query
                            ->whereIn('status', [SpecimenStatus::Logged, SpecimenStatus::InStorage])
                            ->where('site_id', session('currentProject')->members()->where('user_id', Auth::id())->first()->pivot->site_id)
                    )
                    ->using(function (BelongsToMany $relationship, array $data): void {
                        $recordIds = Arr::wrap($data['recordId']);
                        $specimens = Specimen::whereIn('id', $recordIds)->get()->keyBy('id');

                        $pivotData = [];
                        foreach ($recordIds as $id) {
                            $pivotData[$id] = ['priorSpecimenStatus' => $specimens[$id]->status];
                        }

                        $relationship->attach($pivotData);

                        foreach ($specimens as $specimen) {
                            $specimen->setStatus(SpecimenStatus::PreTransfer);
                        }
                    })
                    ->color('primary')
                    ->visible(fn(): bool => $this->getOwnerRecord()->status === ManifestStatus::Open),
                ImportAction::make('importSpecimens')
                    ->label('Upload Specimens')
                    ->importer(ManifestItemImporter::class)
                    ->options([
                        'manifest_id' => $this->getOwnerRecord()->id,
                        'project_id' => session('currentProject')->id,
                        'sourceSite_id' => $this->getOwnerRecord()->sourceSite_id,
                    ])
                    ->visible(fn(): bool => $this->getOwnerRecord()->status === ManifestStatus::Open),
            ])
            ->recordActions([
                ViewAction::make(),
                DetachAction::make()
                    ->before(function (Specimen $record): void {
                        $record->setStatus($record->pivot->priorSpecimenStatus);
                    })
                    ->visible(fn(): bool => $this->getOwnerRecord()->status === ManifestStatus::Open),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make()
                        ->using(function (Collection $records, Table $table): void {
                            /** @var BelongsToMany $relationship */
                            $relationship = $table->getRelationship();

                            $priorStatuses = $records->mapWithKeys(fn(Specimen $record) => [
                                $record->id => $record->pivot->priorSpecimenStatus,
                            ]);

                            $relationship->detach($records);

                            foreach ($records as $record) {
                                $record->setStatus($priorStatuses[$record->id]);
                            }
                        })
                        ->visible(fn(): bool => $this->getOwnerRecord()->status === ManifestStatus::Open),
                ]),
            ]);
    }
}
