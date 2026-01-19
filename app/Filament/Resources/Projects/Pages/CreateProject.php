<?php

// namespace App\Filament\Resources\Projects\Pages;

// use App\Filament\Resources\Projects\ProjectResource;
// use Filament\Resources\Pages\CreateRecord;
// use Illuminate\Support\Facades\Auth;

// class CreateProject extends CreateRecord
// {
//     protected static string $resource = ProjectResource::class;

//     #[\Override]
//     protected function mutateFormDataBeforeCreate(array $data): array
//     {
//         $data['team_id'] = Auth::user()->team_id;

//         return $data;
//     }

//     protected function afterCreate(): void
//     {
//         $this->record->members()->attach($this->record->leader_id, ['role' => 'Admin']);
//     }

//     #[\Override]
//     protected function getRedirectUrl(): string
//     {
//         return static::getResource()::getUrl('view', [
//             'record' => $this->record,
//         ]);
//     }
// }
