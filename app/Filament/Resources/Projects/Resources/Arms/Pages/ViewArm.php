<?php

namespace App\Filament\Resources\Projects\Resources\Studies\Pages;

use App\Filament\Resources\Projects\ProjectResource;
use App\Filament\Resources\Projects\Resources\Arms\ArmResource;
use App\Models\Arm;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\ViewRecord;

class ViewArm extends ViewRecord
{
    protected static string $resource = ArmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // EditAction::make(),
            Action::make('return')
                ->label('Return to Arms')
                ->color('gray')
                ->url(fn(): string => ProjectResource::getUrl('view', ['record' => $this->record->project_id, 'activeRelationManager' => 3])),
            Action::make('edit')
                ->fillForm(fn(Arm $record): array => $record->toArray())
                ->schema([
                    TextInput::make('name')
                        ->default(null),
                    Toggle::make('manual_enrol')
                        ->required()
                        ->inline(false)
                        ->default(false),
                    CheckboxList::make('switcharms')
                        ->options(
                            fn(): array => Arm::where('project_id', $this->record->project_id)
                                ->where('id', '!=', $this->record->id)
                                ->pluck('name', 'id')
                                ->toArray()
                        )
                ])
                ->action(function (array $data, Arm $record): void {
                    $record->fill($data);
                    $record->save();
                }),
        ];
    }
}
