<?php

namespace App\Filament\Imports;

use App\Models\Specimen;
use App\Models\StudySpecimen;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StudySpecimenImporter extends Importer
{
    protected static ?string $model = StudySpecimen::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('barcode')
                ->requiredMapping()
                ->rules(fn($options) => [
                    'required',
                    'max:20',
                    Rule::exists('specimens', 'barcode')
                        ->where('project_id', $options['project']->id),
                ])
                ->fillRecordUsing(function ($record, $state, $options) {
                    $specimen = Specimen::where('barcode', $state)
                        ->where('project_id', $options['project']->id)
                        ->first();

                    if (! $specimen) {
                        return;
                    }

                    $record->specimen_id = $specimen->id;
                }),
        ];
    }

    public function resolveRecord(): StudySpecimen
    {
        $study_specimen = new StudySpecimen;
        $study_specimen->study_id = $this->options['study']->id;

        return $study_specimen;
    }

    protected function afterValidate(): void
    {
        $specimen = Specimen::where('barcode', $this->data['barcode'])->first();

        $exists = StudySpecimen::where('study_id', $this->options['study']->id)
            ->where('specimen_id', $specimen->id)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'event' => "The barcode {$this->data['barcode']} has already been added to this study."
            ]);
        }
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your study specimen import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
