<?php

namespace App\Filament\Exports;

use App\Models\SpecimenType;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class SpecimenTypeExporter extends Exporter
{
    protected static ?string $model = SpecimenType::class;

    public function getFormats(): array
    {
        return [
            ExportFormat::Csv,
        ];
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('name'),
            ExportColumn::make('primary'),
            ExportColumn::make('aliquots'),
            ExportColumn::make('pooled'),
            ExportColumn::make('defaultVolume'),
            ExportColumn::make('volumeUnit'),
            ExportColumn::make('transferDestinations')
                ->formatStateUsing(function ($state): string {
                    if (is_string($state)) {
                        $state = json_decode($state, true);
                    }

                    if (! is_array($state)) {
                        return (string) $state;
                    }

                    return implode(', ', Arr::flatten($state));
                }),
            ExportColumn::make('specimenGroup'),
            ExportColumn::make('labware.name'),
            ExportColumn::make('store'),
            ExportColumn::make('storageDestination'),
            ExportColumn::make('storageSpecimenType'),
            ExportColumn::make('parentSpecimenType.name'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your specimen type export has completed and ' . Str::of('row')->counted($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Str::of('row')->counted($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
