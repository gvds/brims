<?php

namespace App\Filament\Imports;

use App\Enums\EventStatus;
use App\Enums\LabelStatus;
use App\Models\SubjectEvent;
use Closure;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SubjectEventImporter extends Importer
{
    protected static ?string $model = SubjectEvent::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('subjectID')
                ->label('Subject ID')
                ->requiredMapping()
                ->relationship(name: 'subject', resolveUsing: 'subjectID')
                ->rules(fn($options) => [
                    'required',
                    function (string $attribute, $value, Closure $fail) use ($options) {
                        if (! \App\Models\Subject::where('subjectID', $value)
                            ->where('project_id', $options['project']->id)
                            ->exists()) {
                            $fail("The {$attribute} '{$value}' does not exist in this project.");
                        }
                    },
                ]),
            ImportColumn::make('event')
                ->requiredMapping()
                ->relationship(resolveUsing: 'name')
                ->rules(fn($options) => [
                    'required',
                    function ($value, Closure $fail) use ($options) {
                        $exists = \App\Models\Event::where('name', $value)
                            ->whereHas('arm', fn($query) => $query->where('project_id', $options['project']->id))
                            ->exists();

                        if (!$exists) {
                            $fail("The event '{$value}' does not exist in this project.");
                        }
                    },
                ]),
            ImportColumn::make('iteration')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('status')
                ->requiredMapping()
                ->rules([
                    'required',
                    Rule::enum(EventStatus::class),
                ])
                ->castStateUsing(function ($state) {
                    try {
                        return constant(EventStatus::class . '::' . $state)->value;
                    } catch (\Throwable $th) {
                        throw ValidationException::withMessages([
                            'status' => "The status '{$state}' is not valid."
                        ]);
                    }
                }),
            ImportColumn::make('labelstatus')
                ->requiredMapping()
                ->rules([
                    'required',
                    Rule::enum(LabelStatus::class),
                ])
                ->castStateUsing(function ($state) {
                    try {
                        return constant(LabelStatus::class . '::' . $state)->value;
                    } catch (\Throwable $th) {
                        throw ValidationException::withMessages([
                            'labelstatus' => "The label status '{$state}' is not valid."
                        ]);
                    }
                }),
            ImportColumn::make('eventDate')
                ->rules(['nullable', 'date']),
            ImportColumn::make('minDate')
                ->rules(['nullable', 'date', 'before_or_equal:eventDate']),
            ImportColumn::make('maxDate')
                ->rules(['nullable', 'date', 'after_or_equal:eventDate']),
            ImportColumn::make('logDate')
                ->rules(['nullable', 'date', 'after_or_equal:eventDate']),
        ];
    }

    protected function afterValidate(): void
    {
        $subjectID = $this->data['subjectID'];
        $iteration = $this->data['iteration'];
        $event = $this->data['event'];

        if (!$subjectID) {
            return;
        }

        $exists = SubjectEvent::whereHas('subject', fn($query) => $query->where('subjectID', $subjectID))
            ->whereHas('event', fn($query) => $query->where('name', $event)->where('iteration', $iteration))
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'event' => "The combination of subject {$subjectID} event {$event} iteration {$iteration} already exists."
            ]);
        }
    }

    public function resolveRecord(): SubjectEvent
    {
        return new SubjectEvent();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your subject event import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
