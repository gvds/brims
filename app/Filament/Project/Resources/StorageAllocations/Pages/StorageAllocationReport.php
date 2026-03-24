<?php

namespace App\Filament\Project\Resources\StorageAllocations\Pages;

use App\Filament\Project\Resources\StorageAllocations\StorageAllocationResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class StorageAllocationReport extends Page
{
    use InteractsWithRecord;

    protected static string $resource = StorageAllocationResource::class;

    protected string $view = 'filament.project.resources.storage-allocations.pages.storage-allocation-report';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }
}
