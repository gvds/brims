<?php

namespace App\Filament\App\Resources\Studies\RelationManagers;

use App\Enums\StudyRoles;
use App\Models\User;
use Closure;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MembersRelationManager extends RelationManager
{
    protected static string $relationship = 'members';

    #[\Override]
    public function isReadOnly(): bool
    {
        return ! $this->getOwnerRecord()->isLead(Auth::user());
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('fullname')
            ->columns([
                TextColumn::make('fullname')
                    ->searchable([
                        'firstname',
                        'lastname',
                    ]),
                TextColumn::make('pivot.role')
                    ->label('Role'),
                TextColumn::make('team.name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
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
                    ->recordTitle(fn (User $record): string => $record->fullname)
                    ->recordSelectSearchColumns(['firstname', 'lastname'])
                    ->recordSelectOptionsQuery(fn (Builder $query): Builder => $query
                        ->whereIn('users.id', $this->getOwnerRecord()->eligibleMembers()->select('users.id'))
                        ->orderBy('firstname')
                        ->orderBy('lastname'))
                    ->preloadRecordSelect()
                    ->schema(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Select::make('role')
                            ->label('Role')
                            ->options(StudyRoles::class)
                            ->required(),
                    ]),
            ])
            ->recordActions([
                EditAction::make()
                    ->schema([
                        Select::make('role')
                            ->label('Role')
                            ->options(StudyRoles::class)
                            ->required()
                            ->rules([
                                fn (User $record): Closure => function (string $attribute, mixed $value, Closure $fail) use ($record): void {
                                    $value = $value instanceof StudyRoles ? $value->value : $value;

                                    if ($value !== StudyRoles::Lead->value && $this->getOwnerRecord()->isOnlyLead($record)) {
                                        $fail('A study must have at least one lead.');
                                    }
                                },
                            ]),
                    ])
                    ->fillForm(fn (User $record): array => ['role' => $record->pivot->role])
                    ->using(function (User $record, array $data): User {
                        $this->getOwnerRecord()->members()->updateExistingPivot($record->getKey(), ['role' => $data['role']]);

                        return $record;
                    }),
                DetachAction::make()
                    ->disabled(fn (User $record): bool => $this->getOwnerRecord()->isOnlyLead($record))
                    ->tooltip(fn (User $record): ?string => $this->getOwnerRecord()->isOnlyLead($record) ? 'A study must have at least one lead.' : null),
            ]);
    }
}
