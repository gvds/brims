<?php

namespace App\Filament\Resources\Projects\Resources\Arms\Pages;

use App\Filament\Resources\Projects\Resources\Arms\ArmResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditArm extends EditRecord
{
    protected static string $resource = ArmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    public function getRelationManagers(): array
    {
        return [];
    }
}
