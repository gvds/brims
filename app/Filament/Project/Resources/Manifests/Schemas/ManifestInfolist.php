<?php

namespace App\Filament\Project\Resources\Manifests\Schemas;

use App\Models\Specimentype;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class ManifestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(['sm' => 1, 'md' => 2, '2xl' => 4])
                    ->schema([
                        TextEntry::make('user.fullname')
                            ->label('User'),
                        TextEntry::make('sourceSite.name')
                            ->label('Source site'),
                        TextEntry::make('created_at')
                            ->dateTime('Y-m-d H:i')
                            ->placeholder('-'),
                        TextEntry::make('shippedDate')
                            ->date('Y-m-d')
                            ->placeholder('-'),
                        TextEntry::make('status')
                            ->badge(),
                        TextEntry::make('destinationSite.name')
                            ->label('Destination site'),
                        TextEntry::make('receivedBy.fullname')
                            ->label('Received by')
                            ->placeholder('-'),
                        TextEntry::make('receivedDate')
                            ->date('Y-m-d')
                            ->placeholder('-'),
                    ]),
                TextEntry::make('specimenTypes')
                    ->label('Specimen types')
                    ->formatStateUsing(fn($state) => Specimentype::find($state)?->name)
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->size('xs')
                    ->placeholder('-'),
            ])
            ->columns([
                'sm' => 2,
            ]);
    }
}
