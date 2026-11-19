<?php

namespace App\Filament\App\Resources\Studies\Pages;

use App\Enums\StudyRoles;
use App\Filament\App\Resources\Studies\StudyResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateStudy extends CreateRecord
{
    protected static string $resource = StudyResource::class;

    protected static bool $canCreateAnother = false;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    #[\Override]
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['team_id'] = Auth::user()->team_id;

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->getRecord()->members()->attach(Auth::id(), ['role' => StudyRoles::Lead->value]);
    }

    #[\Override]
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }
}
