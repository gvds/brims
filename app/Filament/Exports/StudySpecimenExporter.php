<?php

namespace App\Filament\Exports;

use App\Models\StudySpecimen;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class StudySpecimenExporter extends Exporter
{
    protected static ?string $model = StudySpecimen::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('barcode'),
            ExportColumn::make('specimenType.name'),
            ExportColumn::make('site.name'),
            ExportColumn::make('subjectEvent.event.name')
                ->label('Event Name'),
            ExportColumn::make('subjectEvent.iteration')
                ->label('Event Iteration'),
            ExportColumn::make('subjectEvent.subject.subjectID')
                ->label('SubjectID'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your study specimen export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
