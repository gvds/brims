<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Exports\SubjectExporter;
use App\Filament\Imports\SpecimenImporter;
use App\Filament\Imports\SubjectEventImporter;
use App\Filament\Imports\SubjectImporter;
use App\Filament\Resources\Projects\ProjectResource;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProject extends ViewRecord
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            ActionGroup::make([
                ImportAction::make('subject_import')
                    ->label('Import Subjects')
                    ->color("gray")
                    ->importer(SubjectImporter::class)
                    ->options([
                        'project' => $this->record,
                    ]),
                ImportAction::make('subject_event_import')
                    ->label('Import Subject Events')
                    ->color("gray")
                    ->importer(SubjectEventImporter::class)
                    ->options([
                        'project' => $this->record,
                    ]),
                ImportAction::make('specimen_import')
                    ->label('Import Specimens')
                    ->color("gray")
                    ->importer(SpecimenImporter::class)
                    ->options([
                        'project' => $this->record,
                    ]),
            ])
                ->label('Data Import')
                ->button()
                ->color('info'),
            ActionGroup::make([
                ExportAction::make('subject_export')
                    ->label('Export Subjects')
                    ->color("gray")
                    ->exporter(SubjectExporter::class)
                    ->modifyQueryUsing(fn($query) => $query->where('project_id', $this->record->id)),
            ])
                ->label('Data Export')
                ->button()
                ->color('success'),
        ];
    }
}
