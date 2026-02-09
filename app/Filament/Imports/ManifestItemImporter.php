<?php

namespace App\Filament\Imports;

use App\Enums\SpecimenStatus;
use App\Models\ManifestItem;
use App\Models\Specimen;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class ManifestItemImporter extends Importer
{
    protected static ?string $model = ManifestItem::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('specimen_id')
                ->label('Barcode')
                ->fillRecordUsing(function (ManifestItem $record, string $state, array $options) {
                    $specimen = Specimen::where('barcode', $state)
                        ->where('project_id', $options['project_id'])
                        ->first();
                    $record->specimen_id = $specimen?->id;
                    $record->priorSpecimenStatus = $specimen?->status;
                })
                ->rules(fn(array $options) => [
                    'required',
                    function (string $attribute, mixed $value, \Closure $fail) use ($options) {
                        $specimen = Specimen::where('barcode', $value)
                            ->where('project_id', $options['project_id'])
                            ->first();

                        if (! $specimen) {
                            $fail("The specimen with barcode '{$value}' does not exist in this project.");
                        } elseif (! in_array($specimen->status, [SpecimenStatus::Logged, SpecimenStatus::InStorage])) {
                            $fail("The specimen with barcode '{$value}' is not available for shipping and cannot be added to the manifest.");
                        } elseif ($specimen->site_id !== $options['sourceSite_id']) {
                            $fail("The specimen with barcode '{$value}' does not belong to your site and cannot be added to the manifest.");
                        } elseif (ManifestItem::where('specimen_id', $specimen->id)->where('manifest_id', $options['manifest_id'])->exists()) {
                            $fail("The specimen with barcode '{$value}' is already associated with this manifest.");
                        } elseif (! in_array($specimen->specimenType_id, $options['specimenTypes'] ?? [])) {
                            $fail("The specimen with barcode '{$value}' is of a type that is not allowed on this manifest.");
                        }
                    },
                ]),
        ];
    }

    public function resolveRecord(): ManifestItem
    {
        return new ManifestItem();
    }

    protected function beforeSave(): void
    {
        $this->record->manifest_id = $this->options['manifest_id'];
        $specimen = Specimen::find($this->record->specimen_id);
        $this->record->priorSpecimenStatus = $specimen->status;
    }

    protected function afterSave(): void
    {
        $specimen = Specimen::find($this->record->specimen_id);
        $specimen->update(['status' => SpecimenStatus::PreTransfer]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your manifest items import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
