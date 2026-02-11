<?php

namespace App\Filament\Project\Resources\Projects\Resources\Studies\Resources\Assays\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AssayInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Assay Name'),
                TextEntry::make('assaydefinition.name')
                    ->label('Assay Definition'),
                TextEntry::make('technologyPlatform')
                    ->label('Technology Platform'),
                TextEntry::make('location')
                    ->label('Location'),
                TextEntry::make('uri')
                    ->label('URI'),
                TextEntry::make('additional_fields')
                // Group::make()
                //     ->schema(function (callable $get) {
                //         $additionalFields = [];
                //         if (isset($get('additional_fields')) && is_array($get('additional_fields'))) {
                //             foreach ($get('additional_fields') as $key => $value) {
                //                 $additionalFields[] = TextEntry::make('additional_fields.' . $key)
                //                     ->label(ucwords(str_replace('_', ' ', $key)));
                //             }
                //         }
                //         return $additionalFields;
                //     }),
            ]);
    }
}
