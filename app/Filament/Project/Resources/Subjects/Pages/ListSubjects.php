<?php

namespace App\Filament\Project\Resources\Subjects\Pages;

use App\Filament\Imports\SubjectImporter;
use App\Filament\Project\Resources\Subjects\SubjectResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListSubjects extends ListRecords
{
    protected static string $resource = SubjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->importer(SubjectImporter::class)
        ];
    }
}
