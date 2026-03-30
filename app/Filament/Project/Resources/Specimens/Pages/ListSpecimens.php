<?php

namespace App\Filament\Project\Resources\Specimens\Pages;

use App\actions\LogSpecimenStatus;
use App\Enums\SpecimenStatus;
use App\Filament\Exports\SpecimenExporter;
use App\Filament\Project\Resources\Specimens\SpecimenResource;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\ExportAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Colors\Color;

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
                            ->placeholder('Enter specimen barcodes, comma-separated or one per line')
                            ->required()
                            ->rows(10),
                    ])
                    ->action(function (array $data) {
                        try {
                            $specimen_count = (new LogSpecimenStatus())(SpecimenStatus::Used, $data['barcodes']);

                            Notification::make()
                                ->title('Specimens Logged as Used')
                                ->body($specimen_count . ' specimens have been logged as used.')
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
                            ->placeholder('Enter specimen barcodes, comma-separated or one per line')
                            ->required()
                            ->rows(10),
                    ])
                    ->action(function (array $data) {
                        try {
                            $specimen_count = (new LogSpecimenStatus())(SpecimenStatus::LoggedOut, $data['barcodes']);

                            Notification::make()
                                ->title('Specimens Logged as Used')
                                ->body($specimen_count . ' specimens have been logged out of storage.')
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
                        Toggle::make('thawed')
                            ->label('Increment thaw count?')
                            ->default(true)
                            ->onColor('success')
                            ->required(),
                        Textarea::make('barcodes')
                            ->label('Specimen Barcodes')
                            ->placeholder('Enter specimen barcodes, comma-separated or one per line')
                            ->required()
                            ->rows(10),
                    ])
                    ->action(function (array $data) {
                        try {
                            $specimen_count = (new LogSpecimenStatus())(SpecimenStatus::InStorage, $data['barcodes'], $data['thawed'] ?? false);

                            Notification::make()
                                ->title('Specimens Logged as Used')
                                ->body($specimen_count . ' specimens have been logged back into storage.')
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
                ->label('Update Specimen Status')
                ->button(),
            ExportAction::make('export')
                ->label('Export')
                ->color(Color::Indigo)
                ->exporter(SpecimenExporter::class)
                ->columnMappingColumns(3),
        ];
    }
}
