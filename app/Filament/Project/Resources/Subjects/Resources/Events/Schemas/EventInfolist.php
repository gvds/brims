<?php

namespace App\Filament\Project\Resources\Subjects\Resources\Events\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EventInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('arm_id')
                    ->numeric(),
                TextEntry::make('redcap_event_id')
                    ->numeric(),
                IconEntry::make('autolog')
                    ->boolean(),
                TextEntry::make('eventDate')
                    ->date('Y-m-d'),
                TextEntry::make('minDate')
                    ->date('Y-m-d'),
                TextEntry::make('maxDate')
                    ->date('Y-m-d'),
                TextEntry::make('name_labels')
                    ->numeric(),
                TextEntry::make('subject_event_labels')
                    ->numeric(),
                TextEntry::make('study_id_labels')
                    ->numeric(),
                TextEntry::make('event_order')
                    ->numeric(),
                IconEntry::make('repeatable')
                    ->boolean(),
                IconEntry::make('active')
                    ->boolean(),
            ]);
    }
}
