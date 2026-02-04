<?php

namespace App\Filament\Project\Resources\Manifests\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ManifestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('project.title')
                    ->label('Project'),
                TextEntry::make('user.id')
                    ->label('User'),
                TextEntry::make('sourceSite.name')
                    ->label('Source site'),
                TextEntry::make('destinationSite.name')
                    ->label('Destination site'),
                TextEntry::make('shippedDate')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('receivedBy.id')
                    ->label('Received by')
                    ->placeholder('-'),
                TextEntry::make('receivedDate')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
