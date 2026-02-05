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
                ->label('Specimen Barcode')
                ->relationship(resolveUsing: fn(string $state) => Specimen::where('barcode', $state)
                    ->where('project_id', session('currentProject')->id)
                    ->first()?->id)
                // ->relationship('specimen', 'barcode')
                ->rules([
                    'required',
                    function ($value, $attribute, $fail) {
                        $specimen = Specimen::where('barcode', $value)
                            ->where('project_id', session('currentProject')->id)
                            ->first();

                        if (! $specimen) {
                            $fail("The specimen with barcode '{$value}' does not exist in this project.");
                        } elseif (! in_array($specimen->status, [SpecimenStatus::Logged, SpecimenStatus::InStorage])) {
                            $fail("The specimen with barcode '{$value}' is not available for shipping and cannot be added to the manifest.");
                        } elseif ($specimen->site_id !== $this->options['sourceSite_id']) {
                            $fail("The specimen with barcode '{$value}' does not belong to your site and cannot be added to the manifest.");
                        } elseif (ManifestItem::where('specimen_id', $specimen->id)->where('manifest_id', $this->options['manifest_id'])->exists()) {
                            $fail("The specimen with barcode '{$value}' is already associated with this manifest.");
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
        $this->record->sourceSite_id = $this->options['sourceSite_id'];
        $this->record->priorSpecimenStatus = $this->options['priorSpecimenStatus'];
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
