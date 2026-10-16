<?php

namespace App\Filament\Project\Resources\Projects\RelationManagers;

use App\Models\Project;
use App\Models\User;
use App\Models\Scopes\TeamScope;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class ProgrammesRelationManager extends RelationManager
{
    protected static string $relationship = 'programmes';

    #[\Override]
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        $user = Auth::user();

        return $ownerRecord instanceof Project
            && $user instanceof User
            && $user->can('View:Project');
    }

    #[\Override]
    public function isReadOnly(): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with([
                'team' => fn (BelongsTo $teamQuery) => $teamQuery
                    ->withoutGlobalScope(TeamScope::class)
                    ->select(['id', 'name']),
            ]))
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('funder')
                    ->searchable(),
                TextColumn::make('grantNumber')
                    ->searchable(),
                TextColumn::make('pi.fullname')
                    ->label('PI')
                    ->searchable(),
                TextColumn::make('team.name')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->attachAnother(false),
            ])
            ->recordActions([
                DetachAction::make(),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
