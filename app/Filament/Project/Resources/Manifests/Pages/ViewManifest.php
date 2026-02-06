<?php

namespace App\Filament\Project\Resources\Manifests\Pages;

use App\Enums\ManifestStatus;
use App\Filament\Project\Resources\Manifests\ManifestResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ViewManifest extends ViewRecord
{
    protected static string $resource = ManifestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->visible(fn() => $this->record->status === ManifestStatus::Open),
            Action::make('ship')
                ->label('Ship')
                ->button()
                ->color('info')
                ->requiresConfirmation()
                ->visible(fn($record) => $record->status === ManifestStatus::Open && $record->specimens->count() > 0)
                ->action(function ($record) {
                    try {
                        DB::beginTransaction();
                        $record->ship();
                        Notification::make()
                            ->title('Manifest shipped successfully.')
                            ->success()
                            ->send();
                        DB::commit();
                    } catch (\Exception $th) {
                        DB::rollBack();
                        Notification::make()
                            ->title('Error shipping manifest: ' . $th->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
            Action::make('receive')
                ->label('Receive')
                ->button()
                ->color('info')
                ->requiresConfirmation()
                ->visible(fn($record) => $record->status === ManifestStatus::Shipped && $record->destinationSite_id === session('currentProject')->members()->where('user_id', Auth::id())->first()->pivot->site_id)
                ->action(function ($record, $livewire) {
                    try {
                        DB::beginTransaction();
                        $record->receive();
                        Notification::make()
                            ->title('Manifest marked as received.')
                            ->success()
                            ->send();
                        DB::commit();
                        $livewire->dispatch('refreshSpecimensRelation');
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        Notification::make()
                            ->title('Error receiving manifest: ' . $th->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
            DeleteAction::make()
                ->before(function ($record) {
                    if ($record->status !== ManifestStatus::Open) {
                        throw new \Exception('Only manifests with status "Open" can be deleted.');
                    }
                    $record->specimens()->each(function ($specimen) {
                        $specimen->setStatus($specimen->pivot->priorSpecimenStatus);
                    });
                })
                ->visible(fn() => $this->record->status === ManifestStatus::Open),
        ];
    }
}
