<?php

// namespace App\Filament\Resources\Projects\Pages;

// use App\Filament\Resources\Projects\ProjectResource;
// use Filament\Actions\DeleteAction;
// use Filament\Resources\Pages\EditRecord;
// use Illuminate\Database\Eloquent\Model;

// class EditProject extends EditRecord
// {
//     protected static string $resource = ProjectResource::class;

//     protected function getHeaderActions(): array
//     {
//         return [
//             // ViewAction::make(),
//             DeleteAction::make(),
//         ];
//     }

//     public function getRelationManagers(): array
//     {
//         return [];
//     }

//     #[\Override]
//     protected function handleRecordUpdate(Model $record, array $data): Model
//     {
//         if ($data['leader_id'] !== $record->getOriginal('leader_id')) {
//             if (!$record->members()->updateExistingPivot($data['leader_id'], ['role' => 'Admin'])) {
//                 $record->members()->attach($data['leader_id'], ['role' => 'Admin']);
//             }
//         }
//         $record->update($data);

//         return $record;
//     }


//     #[\Override]
//     protected function getRedirectUrl(): string
//     {
//         return static::getResource()::getUrl('index');
//         // return static::getResource()::getUrl('view', [
//         //     'record' => $this->record,
//         // ]);
//     }
// }
