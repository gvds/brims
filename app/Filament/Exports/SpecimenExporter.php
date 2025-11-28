<?php

namespace App\Filament\Exports;

use App\Enums\SpecimenStatus;
use App\Models\Specimen;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class SpecimenExporter extends Exporter
{
    protected static ?string $model = Specimen::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('barcode'),
            ExportColumn::make('subjectEvent.subject.subjectID')
                ->label('Subject ID'),
            ExportColumn::make('subjectEvent.event.name')
                ->label('Event Name'),
            ExportColumn::make('subjectEvent.iteration')
                ->label('Iteration'),
            ExportColumn::make('specimenType.name'),
            ExportColumn::make('site.name'),
            ExportColumn::make('status')
                ->formatStateUsing(fn(SpecimenStatus $state): string => $state->name),
            ExportColumn::make('parentSpecimen.barcode')
                ->label('Parent Barcode'),
            ExportColumn::make('aliquot'),
            ExportColumn::make('volume'),
            ExportColumn::make('volumeUnit'),
            ExportColumn::make('thawcount'),
            ExportColumn::make('loggedBy.username')
                ->label('Logged By'),
            ExportColumn::make('loggedAt'),
            ExportColumn::make('usedBy.username')
                ->label('Used By'),
            ExportColumn::make('usedAt'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your specimen export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
