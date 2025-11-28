<?php

namespace App\Filament\Exports;

use App\Enums\EventStatus;
use App\Enums\LabelStatus;
use App\Models\SubjectEvent;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class SubjectEventExporter extends Exporter
{
    protected static ?string $model = SubjectEvent::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('subject.subjectID')
                ->label('Subject ID'),
            ExportColumn::make('event.name'),
            ExportColumn::make('iteration'),
            ExportColumn::make('status')
                ->formatStateUsing(fn(EventStatus $state): string => $state->name),
            ExportColumn::make('labelstatus')
                ->formatStateUsing(fn(LabelStatus $state): string => $state->name),
            ExportColumn::make('eventDate'),
            ExportColumn::make('minDate'),
            ExportColumn::make('maxDate'),
            ExportColumn::make('logDate'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your subject event export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
