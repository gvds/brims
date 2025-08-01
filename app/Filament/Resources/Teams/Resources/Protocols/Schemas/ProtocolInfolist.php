<?php

namespace App\Filament\Resources\Teams\Resources\Protocols\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProtocolInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('team_id')
                    ->numeric(),
                TextEntry::make('user_id')
                    ->numeric(),
                TextEntry::make('type'),
                TextEntry::make('type_term_accession_number'),
                TextEntry::make('type_term_reference'),
                TextEntry::make('description'),
                TextEntry::make('uri'),
                TextEntry::make('version'),
                TextEntry::make('parameters_names'),
                TextEntry::make('parameters_term_accession_number'),
                TextEntry::make('parameters_term_reference'),
                TextEntry::make('components_names'),
                TextEntry::make('components_type'),
                TextEntry::make('components_type_term_accession_number'),
                TextEntry::make('components_type_term_reference'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
