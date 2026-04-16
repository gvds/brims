<?php

namespace App\Filament\Project\Resources\Projects\Resources\Arms\Pages;

use App\Filament\Project\Resources\Projects\ProjectResource;
use App\Filament\Project\Resources\Projects\Resources\Arms\ArmResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewArm extends ViewRecord
{
    protected static string $resource = ArmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('return')
                ->label('Return to Project')
                ->color('gray')
                ->url(fn(): string => ProjectResource::getUrl('view', ['record' => $this->record->project_id, 'relation' => 2])),
        ];
    }
}
