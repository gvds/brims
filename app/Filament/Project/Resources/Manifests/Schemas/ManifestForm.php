<?php

namespace App\Filament\Project\Resources\Manifests\Schemas;

use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class ManifestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('destinationSite_id')
                    ->relationship(
                        'destinationSite',
                        'name',
                        fn($query) => $query->whereNot('id', session('currentProject')->members()->find(Auth::id())?->pivot->site_id)
                    )
                    ->required(),
                Select::make('specimenTypes')
                    ->multiple()
                    ->options(fn() => session('currentProject')->specimenTypes()->pluck('name', 'id'))
                    ->disabled(fn($record) => $record?->specimens->count() > 0),
            ])
            ->columns(1)
            ->extraAttributes(['class' => 'min-w-md max-w-max']);
    }
}
