<?php

namespace App\Filament\project\Resources\Projects\Resources\Specimentypes\Pages;

use App\Filament\project\Resources\Projects\Resources\Specimentypes\SpecimentypeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSpecimentype extends ViewRecord
{
    protected static string $resource = SpecimentypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
