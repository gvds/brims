<?php

namespace App\Filament\Project\Resources\Subjects\Pages;

use App\Enums\SubjectStatus;
use App\Filament\Project\Resources\Subjects\SubjectResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditSubject extends EditRecord
{
    protected static string $resource = SubjectResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
                ->visible(fn($record): bool => $record->status !== SubjectStatus::Generated),
            DeleteAction::make(),
        ];
    }

    #[\Override]
    public function getRelationManagers(): array
    {
        return [];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (isset($record->previous_arm_id) && $record->enroldate !== $data['enrolDate']) {
            $data['previousArmBaselineDate'] = $data['enrolDate'];
        }
        $record->update($data);

        return $record;
    }
}
