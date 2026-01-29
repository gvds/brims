<?php

namespace App\Filament\Project\Resources\Projects\Pages;

use App\Filament\Project\Resources\Projects\ProjectResource;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditProject extends EditRecord
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
        ];
    }

    public function getRelationManagers(): array
    {
        return [];
    }

    #[\Override]
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if ($data['leader_id'] !== $record->getOriginal('leader_id')) {
            if (!$record->members()->updateExistingPivot($data['leader_id'], ['role' => 'Admin'])) {
                $record->members()->attach($data['leader_id'], ['role' => 'Admin']);
            }
        }
        $record->update($data);

        return $record;
    }


    #[\Override]
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('view', [
            'record' => $this->record,
        ]);
    }
}
