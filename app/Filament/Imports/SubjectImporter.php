<?php

namespace App\Filament\Imports;

use App\Enums\SubjectStatus;
use App\Models\Arm;
use App\Models\Site;
use App\Models\Subject;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Number;
use Illuminate\Validation\Rule;

class SubjectImporter extends Importer
{
    protected static ?string $model = Subject::class;

    protected static array $staticOptions = [];


    public static function getColumns(): array
    {
        return [
            ImportColumn::make('subjectID')
                ->label('Subject ID')
                ->requiredMapping()
                ->rules(fn($options) => [
                    'required',
                    'unique:subjects,subjectID',
                    'regex:/^' . $options['project']->subjectID_prefix . '\d{' . $options['project']->subjectID_digits . '}$/'
                ]),
            ImportColumn::make('site')
                ->requiredMapping()
                ->relationship(resolveUsing: 'name')
                ->rules(fn($options) => [
                    'required',
                    Rule::exists('sites', 'name')->where('project_id',  $options['project']->id)
                ]),
            ImportColumn::make('user')
                ->requiredMapping()
                ->relationship(resolveUsing: 'username')
                ->rules(['required']),
            ImportColumn::make('firstname')
                ->rules(['max:20']),
            ImportColumn::make('lastname')
                ->rules(['max:30']),
            ImportColumn::make('address'),
            ImportColumn::make('enrolDate')
                ->rules(['date']),
            ImportColumn::make('arm')
                ->relationship(resolveUsing: 'name')
                ->rules(['required']),
            ImportColumn::make('armBaselineDate')
                ->rules(['date']),
            ImportColumn::make('status')
                ->requiredMapping()
                ->numeric()
                ->rules([
                    'required',
                    Rule::enum(SubjectStatus::class)
                ]),
        ];
    }

    public function resolveRecord(): ?Subject
    {
        $subject = new Subject();
        $subject->project_id = $this->options['project']->id;
        return $subject;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your subject import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }

    public function getJobBackoff(): int|array|null
    {
        return [2, 2, 2, 2];
    }
}
