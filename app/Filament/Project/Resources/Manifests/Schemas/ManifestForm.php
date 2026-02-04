<?php

namespace App\Filament\Project\Resources\Manifests\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ManifestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Select::make('user_id')
                //     ->relationship('user', 'id')
                //     ->required(),
                // Select::make('sourceSite_id')
                //     ->relationship('sourceSite', 'name')
                //     ->required(),
                Select::make('destinationSite_id')
                    ->relationship('destinationSite', 'name')
                    ->required(),
            ]);
    }
}
