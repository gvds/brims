<?php

namespace App\Filament\Project\Resources\Projects\Resources\Arms\Pages;

use App\Filament\Project\Resources\Projects\ProjectResource;
use App\Filament\Project\Resources\Projects\Resources\Arms\ArmResource;
use App\Models\Arm;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Grid;

class ViewArm extends ViewRecord
{
    protected static string $resource = ArmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // EditAction::make(),
            Action::make('return')
                ->label('Return to Project')
                ->color('gray')
                ->url(fn(): string => ProjectResource::getUrl('view', ['record' => $this->record->project_id, 'relation' => 3])),
            Action::make('edit')
                ->authorize('update', Arm::class)
                ->fillForm(fn(Arm $record): array => $record->toArray())
                ->schema([
                    Grid::make()
                        ->columns(2)
                        ->components([
                            TextInput::make('name')
                                ->required()
                                ->default(null),
                            Toggle::make('manual_enrol')
                                ->required()
                                ->inline(false)
                                ->default(false),
                        ])
                        ->columnSpanFull(),
                    CheckboxList::make('switcharms')
                        ->options(
                            fn(): array => Arm::where('project_id', $this->record->project_id)
                                ->where('id', '!=', $this->record->id)
                                ->pluck('name', 'id')
                                ->toArray()
                        ),
                ])
                ->action(function (array $data, Arm $record): void {
                    $record->fill($data);
                    $record->save();
                }),
        ];
    }
}
