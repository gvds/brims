<?php

namespace App\Filament\Admin\Resources\LabelSpecifications\Pages;

use App\Filament\Admin\Resources\LabelSpecifications\LabelSpecificationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLabelSpecifications extends ListRecords
{
    protected static string $resource = LabelSpecificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
