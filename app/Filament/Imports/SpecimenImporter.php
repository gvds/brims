<?php

namespace App\Filament\Imports;

use App\Enums\SpecimenStatus;
use App\Models\Specimen;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;
use Illuminate\Validation\Rule;

class SpecimenImporter extends Importer
{
    protected static ?string $model = Specimen::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('barcode')
                ->requiredMapping()
                ->rules(['required', 'max:20']),
            ImportColumn::make('subject')
                ->relationship(resolveUsing: 'subjectID')
                ->rules(['required']),
            ImportColumn::make('event')
                ->requiredMapping()
                ->relationship(resolveUsing: 'name')
                ->rules(['required']),
            ImportColumn::make('specimenType')
                ->requiredMapping()
                ->relationship(resolveUsing: 'name')
                ->rules(['required']),
            ImportColumn::make('site')
                ->requiredMapping()
                ->relationship(resolveUsing: 'name')
                ->rules(['required']),
            ImportColumn::make('status')
                ->requiredMapping()
                ->numeric()
                ->rules([
                    'required',
                    Rule::enum(SpecimenStatus::class)
                ]),
            ImportColumn::make('parentSpecimen')
                ->relationship(resolveUsing: 'barcode'),
            ImportColumn::make('aliquot')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('volume')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('volumeUnit')
                ->rules(['max:5']),
            ImportColumn::make('thawcount')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('loggedBy')
                ->requiredMapping()
                ->relationship(resolveUsing: 'username')
                ->rules(['required']),
            ImportColumn::make('loggedAt')
                ->requiredMapping()
                ->rules(['required', 'datetime']),
            ImportColumn::make('usedBy')
                ->relationship(resolveUsing: 'username')
                ->rules(['required_with:usedAt']),
            ImportColumn::make('usedAt')
                ->rules([
                    'datetime',
                    'required_with:usedBy',
                    'afterOrEqual:loggedAt'
                ]),
        ];
    }

    public function resolveRecord(): Specimen
    {
        return new Specimen();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your specimen import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
