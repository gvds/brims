<?php

namespace App\Filament\Project\Resources\Projects\RelationManagers;

use App\Filament\Project\Resources\Projects\Resources\Arms\ArmResource;
use App\Models\Arm;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
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
                CreateAction::make()
                    ->mutateDataUsing(function (array $data) {
                        $data['arm_num'] = Arm::where('project_id', $this->ownerRecord->id)->max('arm_num') + 1;
                        return $data;
                    }),
            ]);
    }
}
