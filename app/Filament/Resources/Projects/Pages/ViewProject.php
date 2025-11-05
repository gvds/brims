<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Imports\SubjectImporter;
use App\Filament\Resources\Projects\ProjectResource;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
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
                    ->importer(SubjectImporter::class)
                    ->options([
                        'project' => $this->record,
                    ]),
                ImportAction::make('specimen_import')
                    ->label('Import Specimens')
                    ->color("gray")
                    ->importer(SubjectImporter::class)
                    ->options([
                        'project' => $this->record,
                    ]),
            ])
                ->label('Data Import')
                ->button()
                ->color('info'),
        ];
    }
}
