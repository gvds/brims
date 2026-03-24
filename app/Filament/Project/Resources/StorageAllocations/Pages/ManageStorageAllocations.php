<?php

namespace App\Filament\Project\Resources\StorageAllocations\Pages;

use App\Filament\Project\Resources\StorageAllocations\StorageAllocationResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ManageRecords;

class ManageStorageAllocations extends ManageRecords
{
    protected static string $resource = StorageAllocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('allocate')
                ->label('Allocate Storage')
                ->url(fn (): string => static::getResource()::getUrl('allocate')),
        ];
    }
}
