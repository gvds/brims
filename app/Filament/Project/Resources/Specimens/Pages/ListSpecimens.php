<?php

namespace App\Filament\Project\Resources\Specimens\Pages;

use App\Enums\SpecimenStatus;
use App\Filament\Exports\SpecimenExporter;
use App\Filament\Project\Resources\Specimens\SpecimenResource;
use App\Models\Specimen;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\ExportAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Auth;

class ListSpecimens extends ListRecords
{
    protected static string $resource = SpecimenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('log-used')
                    ->label('Log Specimens as Used')
                    ->schema([
                        Textarea::make('barcodes')
                            ->label('Specimen Barcodes')
                            ->placeholder('Enter specimen barcodes, one per line')
                            ->required()
                            ->rows(10),
                    ])
                    ->action(function (array $data) {
                        try {
                            $validStatuses = [SpecimenStatus::Logged, SpecimenStatus::InStorage, SpecimenStatus::LoggedOut];
                            $specimens = $this->processBarcodes($data['barcodes'], $validStatuses);

                            $specimens->each(function ($specimen) {
                                $specimen->status = SpecimenStatus::Used;
                                $specimen->save();
                            });

                            Notification::make()
                                ->title('Specimens Logged as Used')
                                ->body($specimens->count() . ' specimens have been logged as used.')
                                ->success()
                                ->send();
                        } catch (\Throwable $th) {
                            Notification::make()
                                ->title('Error Logging Specimens as Used')
                                ->body($th->getMessage())
                                ->danger()
                                ->persistent()
                                ->send();
                        }
                    }),
                Action::make('log-out')
                    ->label('Log Specimens Out of Storage')
                    ->schema([
                        Textarea::make('barcodes')
                            ->label('Specimen Barcodes')
                            ->placeholder('Enter specimen barcodes, one per line')
                            ->required()
                            ->rows(10),
                    ])
                    ->action(function (array $data) {
                        try {
                            $validStatuses = [SpecimenStatus::InStorage];
                            $specimens = $this->processBarcodes($data['barcodes'], $validStatuses);

                            $specimens->each(function ($specimen) {
                                $specimen->status = SpecimenStatus::LoggedOut;
                                $specimen->save();
                            });

                            Notification::make()
                                ->title('Specimens Logged as Used')
                                ->body($specimens->count() . ' specimens have been logged out of storage.')
                                ->success()
                                ->send();
                        } catch (\Throwable $th) {
                            Notification::make()
                                ->title('Error Logging Specimens out of Storage')
                                ->body($th->getMessage())
                                ->danger()
                                ->persistent()
                                ->send();
                        }
                    }),
                Action::make('log-returned')
                    ->label('Log Specimens Returned to Storage')
                    ->schema([
                        Textarea::make('barcodes')
                            ->label('Specimen Barcodes')
                            ->placeholder('Enter specimen barcodes, one per line')
                            ->required()
                            ->rows(10),
                    ])
                    ->action(function (array $data) {
                        try {
                            $validStatuses = [SpecimenStatus::LoggedOut];
                            $specimens = $this->processBarcodes($data['barcodes'], $validStatuses);

                            $specimens->each(function ($specimen) {
                                $specimen->status = SpecimenStatus::InStorage;
                                $specimen->thawcount++;
                                $specimen->save();
                            });

                            Notification::make()
                                ->title('Specimens Logged as Used')
                                ->body($specimens->count() . ' specimens have been logged back into storage.')
                                ->success()
                                ->send();
                        } catch (\Throwable $th) {
                            Notification::make()
                                ->title('Error Logging Specimens as Returned to Storage')
                                ->body($th->getMessage())
                                ->danger()
                                ->persistent()
                                ->send();
                        }
                    }),
            ])
                ->label('Log Specimen Status')
                ->button(),
            ExportAction::make('export')
                ->label('Export')
                ->color(Color::Indigo)
                ->exporter(SpecimenExporter::class)
                ->columnMappingColumns(3),
        ];
    }

    private function processBarcodes(string $barcodesInput, array $validStatuses)
    {
        $barcodes = array_map('trim', preg_split('/\r\n|\r|\n/', $barcodesInput));
        $barcodes = array_filter($barcodes); // Remove empty lines
        $barcodes = array_unique($barcodes); // Remove duplicate barcodes

        $allSpecimens = Specimen::whereIn('barcode', $barcodes)
            ->where('project_id', session('currentProject')->id)
            ->get();
        $specimens = $allSpecimens
            ->whereIn('status', $validStatuses)
            ->where('site_id', session('currentProject')->members()->firstWhere('user_id', Auth::id())->site_id);

        if (count($barcodes) !== $specimens->count()) {
            $errorMessage = '';

            $invalidStatusSpecimens = $allSpecimens->whereNotIn('status', $validStatuses);

            if ($invalidStatusSpecimens->count()) {
                match ($validStatuses) {
                    [SpecimenStatus::InStorage] => $errorMessage .= 'The following barcodes are not listed as in storage: ' . implode(', ', $invalidStatusSpecimens->pluck('barcode')->toArray()) . '.<br><br>',
                    [SpecimenStatus::LoggedOut] => $errorMessage .= 'The following barcodes are not listed as logged out: ' . implode(', ', $invalidStatusSpecimens->pluck('barcode')->toArray()) . '.<br><br>',
                    default => $errorMessage .= 'The following barcodes are not eligible to be logged as used: ' . implode(', ', $invalidStatusSpecimens->pluck('barcode')->toArray()) . '.<br><br>',
                };
            }

            $invalidSiteSpecimens = $allSpecimens->whereNotIn('site_id', session('currentProject')->members()->firstWhere('user_id', auth()->id())->site_id);
            if ($invalidSiteSpecimens->count()) {
                $errorMessage .= 'The following barcodes are not located at your site: ' . implode(', ', $invalidSiteSpecimens->pluck('barcode')->toArray()) . '.<br><br>';
            }

            $notFoundBarcodes = array_diff($barcodes, $allSpecimens->pluck('barcode')->toArray());
            if ($notFoundBarcodes) {
                $errorMessage .= 'The following barcodes were not found in this project: ' . implode(', ', $notFoundBarcodes) . '.';
            }

            throw new \Exception($errorMessage);
        }

        return $specimens;
    }
}
