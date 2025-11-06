<?php

namespace App\Filament\Imports;

use App\Enums\EventStatus;
use App\Enums\LabelStatus;
use App\Models\SubjectEvent;
use Closure;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\Number;
use Illuminate\Validation\Rule;

class SubjectEventImporter extends Importer
{
    protected static ?string $model = SubjectEvent::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('subject')
                ->requiredMapping()
                ->relationship(resolveUsing: 'subjectID')
                ->rules(['required']),
            ImportColumn::make('event')
                ->requiredMapping()
                ->relationship(resolveUsing: 'name')
                ->rules([
                    'required',
                    fn(Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                        $exists = SubjectEvent::whereHas('subject', function ($query) use ($get) {
                            $query->where('subjectID', $get('subject'));
                        })->whereHas('event', function ($query) use ($value) {
                            $query->where('name', $value);
                        })->exists();

                        if ($exists) {
                            $fail('The combination of subject ' . $get('subject') . ' and event ' . $value . ' already exists.');
                        }
                    }
                ]),
            ImportColumn::make('iteration')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('status')
                ->requiredMapping()
                ->numeric()
                ->rules([
                    'required',
                    Rule::enum(EventStatus::class)
                ]),
            ImportColumn::make('labelstatus')
                ->requiredMapping()
                ->numeric()
                ->rules([
                    'required',
                    Rule::enum(LabelStatus::class)
                ]),
            ImportColumn::make('eventDate')
                ->rules(['date']),
            ImportColumn::make('minDate')
                ->rules(['date', 'beforeOrEqual:eventDate']),
            ImportColumn::make('maxDate')
                ->rules(['date', 'afterOrEqual:eventDate']),
            ImportColumn::make('logDate')
                ->rules(['date', 'afterOrEqual:eventDate']),
        ];
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
