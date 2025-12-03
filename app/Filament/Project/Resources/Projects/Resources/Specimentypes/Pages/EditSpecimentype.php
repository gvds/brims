<?php

namespace App\Filament\Resources\Projects\Resources\Specimentypes\Pages;

use App\Filament\Resources\Projects\Resources\Specimentypes\SpecimentypeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSpecimentype extends EditRecord
{
    protected static string $resource = SpecimentypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
