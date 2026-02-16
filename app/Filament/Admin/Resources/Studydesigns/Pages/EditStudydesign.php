<?php

namespace App\Filament\Admin\Resources\Studydesigns\Pages;

use App\Filament\Admin\Resources\Studydesigns\StudydesignResource;
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
