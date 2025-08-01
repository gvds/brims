<?php

namespace App\Filament\Resources\Teams\Resources\Studydesigns\Pages;

use App\Filament\Resources\Teams\Resources\Studydesigns\StudydesignResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStudydesign extends EditRecord
{
    protected static string $resource = StudydesignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
