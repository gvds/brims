<?php

namespace App\Filament\Admin\Resources\LabelSpecifications\Pages;

use App\Filament\Admin\Resources\LabelSpecifications\LabelSpecificationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLabelSpecification extends EditRecord
{
    protected static string $resource = LabelSpecificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
