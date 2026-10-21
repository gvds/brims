<?php

namespace App\Filament\Imports;

use App\Enums\EventStatus;
use App\Enums\LabelStatus;
use App\Models\Arm;
use App\Models\Event;
use App\Models\Subject;
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

    /** @var array<string, int|null> */
    protected array $subjectIdsBySubjectID = [];

    /** @var array<string, int|null> */
    protected array $armIdsByName = [];

    /** @var array<string, int|null> */
    protected array $eventIdsByArmAndName = [];

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('subjectID')
                ->label('Subject ID')
                ->requiredMapping()
                ->fillRecordUsing(function (SubjectEvent $record, $state, Importer $importer): void {
                    if ($importer instanceof self) {
                        $record->subject_id = $importer->resolveSubjectId($state);
                    }
                })
                ->rules(fn (Importer $importer): array => [
                    'required',
                    function (string $attribute, $value, Closure $fail) use ($importer): void {
                        if (! $importer instanceof self || ! $importer->resolveSubjectId($value)) {
                            $fail("The {$attribute} '{$value}' does not exist in this project.");
                        }
                    },
                ]),
            ImportColumn::make('arm')
                ->requiredMapping()
                ->rules(fn (Importer $importer): array => [
                    'required',
                    function (string $attribute, $value, Closure $fail) use ($importer): void {
                        if (! $importer instanceof self) {
                            return;
                        }

                        if (! $importer->resolveArmId($value)) {
                            $fail('validation.exists')->translate();
                        }
                    },
                ])
                ->fillRecordUsing(fn () => null),
            ImportColumn::make('event')
                ->requiredMapping()
                ->fillRecordUsing(function (SubjectEvent $record, $state, Importer $importer): void {
                    if ($importer instanceof self) {
                        $record->event_id = $importer->resolveEventId($importer->getData()['arm'] ?? '', $state);
                    }
                })
                ->rules(fn ($data, Importer $importer): array => [
                    'required',
                    function (string $attribute, $value, Closure $fail) use ($data, $importer): void {
                        $exists = $importer instanceof self && $importer->resolveEventId($data['arm'] ?? '', $value);

                        if (! $exists) {
                            $fail("The event '{$value}' does not exist in this project.");
                        }
                    },
                ]),
            ImportColumn::make('iteration')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer', 'min:1']),
            ImportColumn::make('status')
                ->requiredMapping()
                ->rules([
                    'required',
                    Rule::enum(EventStatus::class),
                ])
                ->castStateUsing(function ($state) {
                    try {
                        return EventStatus::{$state}->value;
                    } catch (\Throwable) {
                        throw ValidationException::withMessages([
                            'status' => "The event status '{$state}' is not valid. Valid values are: " . implode(', ', array_column(EventStatus::cases(), 'name')) . '.',
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
                        return LabelStatus::{$state}->value;
                    } catch (\Throwable) {
                        throw ValidationException::withMessages([
                            'labelstatus' => "The label status '{$state}' is not valid. Valid values are: " . implode(', ', array_column(LabelStatus::cases(), 'name')) . '.',
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

    protected function resolveSubjectId(string $subjectID): ?int
    {
        if (array_key_exists($subjectID, $this->subjectIdsBySubjectID)) {
            return $this->subjectIdsBySubjectID[$subjectID];
        }

        $subjectId = Subject::query()
            ->where('subjectID', $subjectID)
            ->where('project_id', $this->options['project']->id)
            ->value('id');

        return $this->subjectIdsBySubjectID[$subjectID] = $subjectId === null ? null : (int) $subjectId;
    }

    protected function resolveArmId(string $armName): ?int
    {
        if (array_key_exists($armName, $this->armIdsByName)) {
            return $this->armIdsByName[$armName];
        }

        $armId = Arm::query()
            ->where('name', $armName)
            ->where('project_id', $this->options['project']->id)
            ->value('id');

        return $this->armIdsByName[$armName] = $armId === null ? null : (int) $armId;
    }

    protected function resolveEventId(string $armName, string $eventName): ?int
    {
        $key = json_encode([$armName, $eventName], JSON_THROW_ON_ERROR);

        if (array_key_exists($key, $this->eventIdsByArmAndName)) {
            return $this->eventIdsByArmAndName[$key];
        }

        $eventId = Event::query()
            ->where('name', $eventName)
            ->whereHas('arm', fn ($query) => $query
                ->where('project_id', $this->options['project']->id)
                ->where('name', $armName))
            ->value('id');

        return $this->eventIdsByArmAndName[$key] = $eventId === null ? null : (int) $eventId;
    }

    protected function afterValidate(): void
    {
        $subjectID = $this->data['subjectID'];
        $iteration = $this->data['iteration'];
        $event = $this->data['event'];
        $arm = $this->data['arm'];

        if (! $subjectID) {
            return;
        }

        $subjectId = $this->resolveSubjectId($subjectID);
        $eventId = $this->resolveEventId($arm, $event);

        if (! $subjectId || ! $eventId) {
            return;
        }

        $exists = SubjectEvent::query()
            ->withoutGlobalScopes()
            ->where('subject_id', $subjectId)
            ->where('event_id', $eventId)
            ->where('iteration', $iteration)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'event' => "The combination of subject '{$subjectID}' arm '{$arm}' event '{$event}' iteration {$iteration} already exists.",
            ]);
        }
    }

    #[\Override]
    public function resolveRecord(): SubjectEvent
    {
        return new SubjectEvent;
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
