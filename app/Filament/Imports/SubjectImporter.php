<?php

namespace App\Filament\Imports;

use App\Models\Arm;
use App\Models\Site;
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
                // ->helperText(fn() => 'A string comprising a prefix of ' . $this->options['project']->subjectID_prefix . ' followed by ' . $this->options['project']->subjectID_digits . ' digits.')
                ->rules(fn() => [
                    'required',
                    'unique:subjects,subjectID',
                    'regex:/^' . $this->options['project']->subjectID_prefix . '\d{' . $this->options['project']->subjectID_digits . '}$/'
                ]),
            ImportColumn::make('site')
                ->requiredMapping()
                ->relationship()
                // ->helperText(fn() => 'Must be one of the sites associated with the current project (' . $this->options['project']->sites->pluck('name')->join(', ') . ').')
                ->rules(['required'])
                ->castStateUsing(fn($state) => Site::where('name', $state)->where('project_id', $this->options['project'])->first()?->id),
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
                ->relationship()
                ->rules(['required'])
                ->castStateUsing(fn($state) => Arm::where('name', $state)->where('project_id', $this->options['project'])->first()?->id),
            ImportColumn::make('armBaselineDate')
                ->rules(['date']),
            ImportColumn::make('status')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
        ];
    }

    public function resolveRecord(): ?Subject
    {
        $subject = new Subject;
        $subject->project_id = $this->options['project'] ?? null;

        return $subject;
    }

    // protected function beforeValidate(): void
    // {
    //     // Get project ID from options
    //     $project = $this->options['project'] ?? null;

    //     // Skip validation if no subjectID data
    //     if (! isset($this->data['subjectID'])) {
    //         return;
    //     }

    //     // Skip validation if project not found or missing required fields
    //     if (! $project || ! $project->subjectID_prefix || ! $project->subjectID_digits) {
    //         return;
    //     }

    //     // Build validation pattern
    //     $pattern = '/^' . preg_quote($project->subjectID_prefix, '/') . '\d{' . $project->subjectID_digits . '}$/';

    //     // Validate subjectID format
    //     if (! preg_match($pattern, $this->data['subjectID'])) {
    //         throw new \Exception(
    //             "SubjectID must start with '{$project->subjectID_prefix}' followed by {$project->subjectID_digits} digits."
    //         );
    //     }
    // }

    // protected function beforeFill(): void
    // {
    //     $project = $this->options['project'] ?? null;

    //     if (! $project) {
    //         return;
    //     }

    //     // Resolve site name to site_id
    //     // if (isset($this->data['site']) && is_string($this->data['site'])) {
    //     //     $site = $project->sites()->where('name', $this->data['site'])->first();
    //     //     $this->data['site_id'] = $site?->id;
    //     //     unset($this->data['site']);
    //     // }

    //     // Resolve arm name to arm_id
    //     // if (isset($this->data['arm']) && is_string($this->data['arm'])) {
    //     //     $arm = $project->arms()->where('name', $this->data['arm'])->first();
    //     //     $this->data['arm_id'] = $arm?->id;
    //     //     unset($this->data['arm']);
    //     // }
    // }

    protected function beforeSave(): void
    {
        $data['project_id'] = $this->options['project']->id;
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
