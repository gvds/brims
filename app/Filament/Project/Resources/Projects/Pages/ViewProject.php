<?php

namespace App\Filament\Project\Resources\Projects\Pages;

use App\Filament\Exports\SpecimenExporter;
use App\Filament\Exports\SubjectEventExporter;
use App\Filament\Exports\SubjectExporter;
use App\Filament\Imports\SpecimenImporter;
use App\Filament\Imports\SubjectEventImporter;
use App\Filament\Imports\SubjectImporter;
use App\Filament\Project\Resources\Projects\ProjectResource;
use App\Models\Specimen;
use App\Models\Subject;
use App\Models\SubjectEvent;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Gate;

class ViewProject extends ViewRecord
{
    protected static string $resource = ProjectResource::class;

    public function mount(int|string $record): void
    {
        parent::mount($record);

        // Set the permissions team ID based on the current project
        // setPermissionsTeamId($this->record->id);
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            ActionGroup::make([
                ImportAction::make('subject_import')
                    ->authorize(['create', 'update'], Subject::class)
                    ->label('Import Subjects')
                    ->color('gray')
                    ->importer(SubjectImporter::class)
                    ->options([
                        'project' => $this->record,
                    ]),
                ImportAction::make('subject_event_import')
                    ->authorize(['create', 'update'], SubjectEvent::class)
                    ->label('Import Subject Events')
                    ->color('gray')
                    ->importer(SubjectEventImporter::class)
                    ->options([
                        'project' => $this->record,
                    ]),
                ImportAction::make('specimen_import')
                    ->authorize(['create', 'update'], Specimen::class)
                    ->label('Import Specimens')
                    ->color('gray')
                    ->importer(SpecimenImporter::class)
                    ->options([
                        'project' => $this->record,
                    ]),
            ])
                ->label('Data Import')
                ->button()
                ->color(Color::Indigo),
            ActionGroup::make([
                ExportAction::make('subject_export')
                    ->visible(fn (): bool => Gate::allows('viewAny', Subject::class))
                    ->label('Export Subjects')
                    ->color('gray')
                    ->exporter(SubjectExporter::class)
                    ->columnMapping(false)
                    ->modifyQueryUsing(fn ($query) => $query->where('project_id', $this->record->id)),
                ExportAction::make('subject_event_export')
                    ->visible(fn (): bool => Gate::allows('viewAny', SubjectEvent::class))
                    ->label('Export Subject Events')
                    ->color('gray')
                    ->exporter(SubjectEventExporter::class)
                    ->columnMapping(false)
                    ->modifyQueryUsing(fn ($query) => $query->whereHas('subject', fn ($query) => $query->where('project_id', $this->record->id))),
                ExportAction::make('specimen_export')
                    ->visible(fn (): bool => Gate::allows('viewAny', Specimen::class))
                    ->label('Export Specimens')
                    ->color('gray')
                    ->exporter(SpecimenExporter::class)
                    ->columnMapping(false)
                    ->modifyQueryUsing(fn ($query) => $query->where('project_id', $this->record->id)),
            ])
                ->label('Data Export')
                ->button()
                ->color(Color::Indigo),
        ];
    }
}
