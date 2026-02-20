<?php

namespace App\Filament\Project\Resources\Publications\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PublicationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title')
                    ->columnSpanFull(),
                TextEntry::make('authors')
                    ->columnSpanFull(),
                TextEntry::make('pubmed_id')
                    ->prefix('PMID'),
                TextEntry::make('doi'),
                TextEntry::make('publication_date'),
                TextEntry::make('publication_status')
                    ->badge(),
            ]);
    }
}
