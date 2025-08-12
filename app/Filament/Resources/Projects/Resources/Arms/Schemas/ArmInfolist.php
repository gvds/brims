<?php

namespace App\Filament\Resources\Projects\Resources\Arms\Schemas;

use App\Models\Arm;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ArmInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                IconEntry::make('manual_enrol'),
                TextEntry::make('arm_num')
                    ->label('Arm Number'),
                TextEntry::make('redcap_arm_id')
                    ->label('REDCap Arm ID'),
                TextEntry::make('switcharms')
                    ->label('Switchable Arms')
                    ->inlineLabel(true)
                    ->state(fn($record) => Arm::whereIn('id', $record->switcharms ?? [])->pluck('name')->implode(' | '))
                    ->placeholder('--- No Switchable Arms ---'),
            ])
            ->columns(['default' => 1, 'sm' => 2, 'lg' => 4])
            ->extraAttributes(['class' => 'border border-gray-200 rounded-lg py-4 px-5 bg-gray-50 dark:bg-zinc-900 dark:border-zinc-800']);
    }
}
