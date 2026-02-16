<?php

namespace App\Filament\Admin\Resources\Studydesigns\Pages;

use App\Filament\Admin\Resources\Studydesigns\StudydesignResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStudydesigns extends ListRecords
{
    protected static string $resource = StudydesignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
