<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Resources\Projects\ProjectResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;

    #[\Override]
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['team_id'] = auth()->user()->team_id;

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->members()->attach($this->record->leader_id, ['role' => 'Admin']);
    }
}
