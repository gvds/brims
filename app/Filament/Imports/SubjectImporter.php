<?php

namespace App\Filament\Imports;

use App\Models\Subject;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class SubjectImporter extends Importer
{
    protected static ?string $model = Subject::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('subjectID')
                ->requiredMapping()
                ->helperText(function () {
                    $project = session('currentProject');
                    return 'A string comprising a prefix of ' . $project->subjectID_prefix . ' followed by ' . $project->subjectID_digits . ' digits.';
                })
                ->rules(['required', 'unique:subjects,subjectID', 'regex:/^' . session('currentProject')->subjectID_prefix . '\d{' . session('currentProject')->subjectID_digits . '}$/']),
            // ImportColumn::make('project')
            //     ->requiredMapping()
            //     ->relationship()
            //     ->rules(['required']),
            ImportColumn::make('site')
                ->requiredMapping()
                ->relationship()
                ->helperText(function () {
                    $project = session('currentProject');
                    return 'Must be one of the sites associated with the current project (' . $project->sites->pluck('name')->join(', ') . ').';
                })
                ->rules(['required']),
            // ImportColumn::make('user')
            //     ->requiredMapping()
            //     ->relationship()
            //     ->rules(['required']),
            ImportColumn::make('firstname')
                ->rules(['max:20']),
            ImportColumn::make('lastname')
                ->rules(['max:30']),
            ImportColumn::make('address'),
            ImportColumn::make('enrolDate')
                ->rules(['date']),
            ImportColumn::make('arm')
                ->relationship(),
            ImportColumn::make('armBaselineDate')
                ->rules(['date']),
            // ImportColumn::make('previousArm')
            //     ->relationship(),
            // ImportColumn::make('previousArmBaselineDate')
            //     ->rules(['date']),
            ImportColumn::make('status')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
        ];
    }

    public function resolveRecord(): Subject
    {
        return new Subject();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your subject import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
