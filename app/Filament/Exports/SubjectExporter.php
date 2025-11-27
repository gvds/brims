<?php

namespace App\Filament\Exports;

use App\Models\Subject;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class SubjectExporter extends Exporter
{
    protected static ?string $model = Subject::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('subjectID')
                ->label('SubjectID'),
            ExportColumn::make('site.name'),
            ExportColumn::make('user.username'),
            ExportColumn::make('firstname'),
            ExportColumn::make('lastname'),
            ExportColumn::make('address'),
            ExportColumn::make('enrolDate'),
            ExportColumn::make('arm.name'),
            ExportColumn::make('armBaselineDate'),
            ExportColumn::make('status'),
        ];
    }


    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your subject export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
