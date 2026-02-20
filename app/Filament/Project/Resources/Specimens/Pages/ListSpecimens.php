<?php

namespace App\Filament\Project\Resources\Specimens\Pages;

use App\Filament\Exports\SpecimenExporter;
use App\Filament\Project\Resources\Specimens\SpecimenResource;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;

class ListSpecimens extends ListRecords
{
    protected static string $resource = SpecimenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make('export')
                ->label('Export')
                ->color('info')
                ->exporter(SpecimenExporter::class),
        ];
    }
}
