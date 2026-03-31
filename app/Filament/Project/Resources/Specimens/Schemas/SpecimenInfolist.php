<?php

namespace App\Filament\Project\Resources\Specimens\Schemas;

use App\Models\Specimen;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SpecimenInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('barcode'),
                TextEntry::make('subjectEvent.event.name')
                    ->label('Subject Event'),
                TextEntry::make('specimenType.name')
                    ->label('Specimen Type'),
                TextEntry::make('parentSpecimen.barcode')
                    ->label('Parent Specimen'),
                TextEntry::make('site.name')
                    ->label('Site'),
                TextEntry::make('status'),
                TextInput::make('aliquot'),
                TextInput::make('volume')
                    ->postfix(fn(Specimen $record) => $record->volumeUnit),
                TextInput::make('thawcount')
                    ->label('Thaw Count'),
                TextEntry::make('loggedBy.fullname')
                    ->label('Logged By'),
                TextEntry::make('loggedAt'),
                RepeatableEntry::make('auditLogs')
                    ->label('Audit Logs')
                    ->table([
                        TableColumn::make('Previous Status'),
                        TableColumn::make('New Status'),
                        TableColumn::make('Changed By'),
                        TableColumn::make('Changed At'),
                    ])
                    ->schema([
                        TextEntry::make('previous_status'),
                        TextEntry::make('new_status'),
                        TextEntry::make('changedBy.fullname'),
                        TextEntry::make('created_at'),
                    ])
                    ->columns(4)
                    ->columnSpanFull(),
            ])
            ->columns(3);
    }
}
