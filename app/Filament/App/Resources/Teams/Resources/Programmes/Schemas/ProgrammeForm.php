<?php

namespace App\Filament\App\Resources\Teams\Resources\Programmes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class ProgrammeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(100),
                TextInput::make('funder')
                    ->required()
                    ->maxLength(150),
                TextInput::make('grantNumber')
                    ->default(null)
                    ->maxLength(100),
                Select::make('user_id')
                    ->relationship(
                        'pi',
                        modifyQueryUsing: fn($query, $livewire) => $query->where('team_id', $livewire->getParentRecord()->id)
                    )
                    ->getOptionLabelFromRecordUsing(fn(Model $record) => $record->fullname)
                    ->searchable(['firstname', 'lastname'])
                    ->searchable()
                    ->preload(),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
            ])
            ->extraAttributes(['class' => 'w-1/3']);
    }
}
