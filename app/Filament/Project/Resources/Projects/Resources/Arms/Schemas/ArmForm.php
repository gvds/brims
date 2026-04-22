<?php

namespace App\Filament\Project\Resources\Projects\Resources\Arms\Schemas;

use App\Models\Arm;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Livewire\Component;

class ArmForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->columns(2)
                    ->components([
                        TextInput::make('name')
                            ->required()
                            ->autocomplete(false)
                            ->default(null),
                        Toggle::make('manual_enrol')
                            ->required()
                            ->inline(false)
                            ->default(false),
                    ])
                    ->columnSpanFull(),
                CheckboxList::make('switcharms')
                    ->label('Switchable Arms')
                    ->options(
                        function (?Arm $record, Component $livewire): array {
                            $projectId = $record ? $record->project_id : $livewire->getOwnerRecord()->id;
                            return Arm::where('project_id', $projectId)
                                ->when($record, fn($query) => $query->whereNot('id', $record->id))
                                ->pluck('name', 'id')
                                ->toArray();
                        }
                    ),
            ]);
    }
}
