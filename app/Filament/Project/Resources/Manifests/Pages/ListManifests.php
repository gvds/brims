<?php

namespace App\Filament\Project\Resources\Manifests\Pages;

use App\Enums\ManifestStatus;
use App\Filament\Project\Resources\Manifests\ManifestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Support\Facades\Auth;

class ListManifests extends ListRecords
{
    protected static string $resource = ManifestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'sent' => Tab::make('Sent Manifests')
                ->query(fn ($query) => $query->where('sourceSite_id', session('currentProject')
                    ->members()
                    ->where('user_id', Auth::id())
                    ->first()
                    ->pivot
                    ->site_id))
                ->label('Sent Manifests'),
            'received' => Tab::make('Received Manifests')
                ->query(fn ($query) => $query
                    ->where('status', '!=', ManifestStatus::Open)
                    ->where('destinationSite_id', session('currentProject')
                        ->members()
                        ->where('user_id', Auth::id())
                        ->first()
                        ->pivot
                        ->site_id))
                ->label('Received Manifests'),
        ];
    }
}
