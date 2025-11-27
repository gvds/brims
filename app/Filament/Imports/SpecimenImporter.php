<?php

namespace App\Filament\Imports;

use App\Enums\SpecimenStatus;
use App\Models\Specimen;
use Closure;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SpecimenImporter extends Importer
{
    protected static ?string $model = Specimen::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('barcode')
                ->requiredMapping()
                ->rules(['required', 'max:20']),
            ImportColumn::make('subjectID')
                ->requiredMapping()
                ->fillRecordUsing(fn() => null)
                ->rules(
                    fn($options) => [
                        'required',
                        function (string $attribute, $value, Closure $fail) use ($options) {
                            if (! \App\Models\Subject::where('subjectID', $value)
                                ->where('project_id', $options['project']->id)
                                ->exists()) {
                                $fail("The {$attribute} '{$value}' does not exist in this project.");
                            }
                        },
                    ]
                ),
            ImportColumn::make('event')
                ->requiredMapping()
                ->fillRecordUsing(fn() => null)
                ->rules(fn($options) => [
                    'required',
                    function (string $attribute, $value, Closure $fail) use ($options) {
                        $exists = \App\Models\Event::where('name', $value)
                            ->whereHas('arm', fn($query) => $query->where('project_id', $options['project']->id))
                            ->exists();

                        if (! $exists) {
                            $fail("The event '{$value}' does not exist in this project.");
                        }
                    },
                ]),
            ImportColumn::make('iteration')
                ->requiredMapping()
                ->fillRecordUsing(fn() => null)
                ->numeric()
                ->rules(['required', 'integer', 'min:1']),
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
                ->rules([
                    'required',
                    Rule::enum(SpecimenStatus::class),
                ])
                ->castStateUsing(function ($state) {
                    try {
                        return constant(SpecimenStatus::class . '::' . $state)->value;
                    } catch (\Throwable $th) {
                        throw ValidationException::withMessages([
                            'status' => "The status '{$state}' is not valid. Valid values are: " . implode(', ', array_column(SpecimenStatus::cases(), 'name')) . '.',
                        ]);
                    }
                }),
            ImportColumn::make('parentSpecimen')
                ->relationship(resolveUsing: 'barcode')
                ->requiredMapping(),
            ImportColumn::make('aliquot')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('volume')
                ->requiredMapping()
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
                ->rules(['required', 'date']),
            ImportColumn::make('usedBy')
                ->relationship(resolveUsing: 'username')
                ->requiredMapping()
                ->rules(['nullable', 'required_with:usedAt']),
            ImportColumn::make('usedAt')
                ->requiredMapping()
                ->rules([
                    'nullable',
                    'date_format:Y-m-d H:i:s',
                    'required_with:usedBy',
                    'after_or_equal:loggedAt',
                ]),
        ];
    }

    protected function afterValidate(): void
    {
        $project = $this->options['project'];
        $barcode = $this->data['barcode'];

        if (! $project) {
            return;
        }

        $exists = Specimen::where('project_id', $project->id)
            ->where('barcode', $barcode)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'barcode' => "The barcode {$barcode} already exists in this project.",
            ]);
        }

        // Validate that the subject event exists with the given iteration
        $subject = \App\Models\Subject::where('subjectID', $this->data['subjectID'])
            ->where('project_id', $project->id)
            ->first();

        if ($subject) {
            $event = \App\Models\Event::where('name', $this->data['event'])
                ->whereHas('arm', fn($query) => $query->where('project_id', $project->id))
                ->first();

            if ($event) {
                $subjectEventExists = \App\Models\SubjectEvent::where('subject_id', $subject->id)
                    ->where('event_id', $event->id)
                    ->where('iteration', $this->data['iteration'])
                    ->exists();

                if (! $subjectEventExists) {
                    throw ValidationException::withMessages([
                        'event' => "The subject event '{$this->data['event']}' with iteration {$this->data['iteration']} does not exist for this subject.",
                    ]);
                }
            }
        }
    }

    protected function beforeSave(): void
    {
        $project = $this->options['project'];

        // Resolve subject_event_id from event name and iteration
        $subject = \App\Models\Subject::where('subjectID', $this->data['subjectID'])
            ->where('project_id', $project->id)
            ->first();

        if (! $subject) {
            throw ValidationException::withMessages([
                'subjectID' => "Subject '{$this->data['subjectID']}' not found in this project.",
            ]);
        }

        $event = \App\Models\Event::where('name', $this->data['event'])
            ->whereHas('arm', fn($query) => $query->where('project_id', $project->id))
            ->first();

        if (! $event) {
            throw ValidationException::withMessages([
                'event' => "Event '{$this->data['event']}' not found in this project.",
            ]);
        }

        $subjectEvent = \App\Models\SubjectEvent::where('subject_id', $subject->id)
            ->where('event_id', $event->id)
            ->where('iteration', $this->data['iteration'])
            ->first();

        if (! $subjectEvent) {
            throw ValidationException::withMessages([
                'event' => "Event '{$this->data['event']}' with iteration {$this->data['iteration']} does not exist for subject '{$this->data['subjectID']}'.",
            ]);
        }

        $this->record->subject_event_id = $subjectEvent->id;
    }

    public function resolveRecord(): Specimen
    {
        $specimen = new Specimen;
        $specimen->project_id = $this->options['project']->id;

        return $specimen;
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
