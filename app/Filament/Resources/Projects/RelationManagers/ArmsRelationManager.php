<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use App\Filament\Resources\Projects\Resources\Arms\ArmResource;
use App\Models\Arm;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Tables\Table;

class ArmsRelationManager extends RelationManager
{
    protected static string $relationship = 'arms';

    protected static ?string $relatedResource = ArmResource::class;

    #[\Override]
    public function isReadOnly(): bool
    {
        return false;
    }


    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                // CreateAction::make(),
                Action::make('create')
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
                                fn(): array => Arm::where('project_id', $this->ownerRecord->id)
                                    ->pluck('name', 'id')
                                    ->toArray()
                            )
                    ])
                    ->action(function (array $data): void {
                        $data['project_id'] = $this->ownerRecord->id;
                        $data['arm_num'] = Arm::where('project_id', $this->ownerRecord->id)->max('arm_num') + 1;
                        Arm::create($data);
                    }),
            ]);
    }
}
