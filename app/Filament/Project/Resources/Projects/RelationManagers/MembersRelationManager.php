<?php

namespace App\Filament\Project\Resources\Projects\RelationManagers;

use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\Role;
use App\Models\Site;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\HtmlString;

use Filament\Facades\Filament;
use Spatie\Permission\PermissionRegistrar;

class MembersRelationManager extends RelationManager
{
    protected static string $relationship = 'members';

    #[\Override]
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        $tenantId = Filament::getTenant()?->getKey();
        $permissionTeamId = app(PermissionRegistrar::class)->getPermissionsTeamId();

        logger()->debug('Project authorization scope', [
            'tenant_id' => $tenantId,
            'permission_team_id' => $permissionTeamId,
            'project_id' => $ownerRecord->getKey(),
        ]);

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

    // public function form(Schema $schema): Schema
    // {
    //     return $schema
    //         ->components([
    //             TextInput::make('username')
    //                 ->required(),
    //             TextInput::make('firstname')
    //                 ->required(),
    //             TextInput::make('lastname')
    //                 ->required(),
    //             TextInput::make('email')
    //                 ->email()
    //                 ->required(),
    //             TextInput::make('telephone')
    //                 ->tel()
    //                 ->default(null),
    //             TextInput::make('institution')
    //                 ->default(null),
    //             ComponentsToggle::make('active')
    //                 ->required(),
    //         ]);
    // }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitle(fn(User $record): string => "{$record->firstname} {$record->lastname}")
            ->columns([
                TextColumn::make('fullname')
                    ->searchable(),
                TextColumn::make('pivot.role.name'),
                TextColumn::make('team.name')
                    ->label('Team'),
                TextColumn::make('site_name')
                    ->label('Site')
                    ->getStateUsing(function (User $record) {
                        if (!$record->pivot->site_id) {
                            return null;
                        }

                        return Site::find($record->pivot->site_id)?->name;
                    }),
                TextColumn::make('projectSubstitute.fullname')
                    ->label('Substitute')
                    ->icon(fn(User $record): ?string => $this->canManageSubstitute($record) ? 'heroicon-o-pencil' : null)
                    ->badge()
                    ->placeholder(fn(User $record): HtmlString => new HtmlString(
                        Blade::render(
                            '<x-heroicon-o-pencil class="w-4 h-4 inline mr-1 '
                                . ($this->canManageSubstitute($record) ? '' : 'invisible')
                                . '" />None',
                        ),
                    ))
                    ->action(
                        Action::make('selectSubstitute')
                            ->label('Select Substitute')
                            ->icon('heroicon-o-user-plus')
                            ->authorize(fn(User $record): bool => $this->canManageSubstitute($record))
                            ->schema([
                                Select::make('substitute_id')
                                    ->label('Select Substitute')
                                    ->placeholder('Choose a substitute...')
                                    ->options(fn(User $record): array => $this->getSubstituteOptions($record))
                                    ->searchable()
                                    ->preload()
                                    ->nullable(),
                            ])
                            ->action(function (User $record, array $data): void {
                                $substituteId = $data['substitute_id'] ?? null;
                                $authorizationArguments = [$record->pivot, $this->ownerRecord];

                                if ($substituteId !== null) {
                                    $substitute = $this->ownerRecord->members()
                                        ->whereKey($substituteId)
                                        ->first();

                                    if (! $substitute) {
                                        throw new AuthorizationException;
                                    }

                                    $authorizationArguments[] = $substitute;
                                }

                                if (! Gate::allows('setSubstitute', $authorizationArguments)) {
                                    throw new AuthorizationException;
                                }

                                $this->ownerRecord->members()
                                    ->updateExistingPivot($record->id, [
                                        'substitute_id' => $substituteId,
                                    ]);
                            })
                            ->fillForm(fn(User $record): array => [
                                'substitute_id' => $record->pivot->substitute_id,
                            ])
                            ->modalHeading(fn(User $record): string => "Select Substitute for {$record->fullname}")
                            ->modalDescription('Choose a substitute from members of the same project site.')
                            ->modalSubmitActionLabel('Save Substitute')
                            ->modalCancelActionLabel('Cancel'),
                    ),
            ])
            ->headerActions([
                AttachAction::make()
                    ->authorize('attach', ProjectMember::class)
                    ->recordSelectOptionsQuery(fn(Builder $query) => $query->where('active', true))
                    ->preloadRecordSelect()
                    ->recordSelectSearchColumns(['firstname', 'lastname'])
                    ->schema(fn(AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Select::make('role_id')
                            ->label("Role")
                            ->options(fn()  => Role::where('project_id', $this->ownerRecord->id)->pluck('name', 'id'))
                            ->required(),
                        Select::make('site_id')
                            ->label('Site')
                            ->options(
                                Site::where('project_id', $this->ownerRecord->id)->pluck('name', 'id')
                            ),
                    ]),
            ])
            ->recordActions([
                EditAction::make()
                    ->schema([
                        Select::make('role_id')
                            ->label('Role')
                            ->options(fn()  => Role::where('project_id', $this->ownerRecord->id)->pluck('name', 'id'))
                            ->required()
                            ->disabled(fn(User $record): bool => $record->id === $this->ownerRecord->leader_id),
                        Select::make('site_id')
                            ->label('Site')
                            ->options(
                                Site::where('project_id', $this->ownerRecord->id)->pluck('name', 'id')
                            ),
                        TextInput::make('redcap_token')
                            ->visible(fn(): bool => $this->ownerRecord->redcapProject_id !== null),
                    ])
                    ->before(function (User $record, array $data): void {
                        foreach ($record->roles as $role) {
                            $record->removeRole($role);
                        }
                    })
                    ->after(function (User $record, array $data): void {
                        // If the site_id has changed, and the current substitute is not in the new site, clear it
                        if (isset($data['site_id']) && $data['site_id'] != $record->pivot->site_id) {
                            $newSiteId = $data['site_id'];
                            $currentSubstituteId = $record->pivot->substitute_id;

                            if ($currentSubstituteId) {
                                $substituteSiteId = ProjectMember::where('project_id', $this->ownerRecord->id)
                                    ->where('user_id', $currentSubstituteId)
                                    ->value('site_id');

                                if ($substituteSiteId != $newSiteId) {
                                    // Clear the substitute_id
                                    $this->ownerRecord->members()
                                        ->updateExistingPivot($record->id, [
                                            'substitute_id' => null,
                                        ]);
                                }
                            }
                        }

                        $role = Role::find($data['role_id']);
                        if ($role) {
                            setPermissionsTeamId($this->ownerRecord->id);
                            $record->syncRoles($role);
                        }
                    }),
                DetachAction::make()
                    ->authorize('detach', ProjectMember::class)
                    ->visible(fn(User $record): bool => $record->id !== $this->ownerRecord->leader_id),
                // ->before(
                //     function (User $record, DetachAction $action) {
                //         if ($record->pivot->pivotParent->members->count() === 1) {
                //             Notification::make()
                //                 ->title('Empty Team')
                //                 ->body('A team cannot be empty. Please add another member before removing this one.')
                //                 ->duration(10000)
                //                 ->danger()
                //                 ->color('danger')
                //                 ->send();
                //             $action->cancel();
                //         }
                //         $project_admin_count = ProjectMember::where('project_id', $record->pivot->project_id)
                //             ->where('role', 'Admin')
                //             ->count();
                //         if ($record->pivot->role == 'Admin' && $project_admin_count === 1) {
                //             Notification::make()
                //                 ->title('Project requires at least one admin')
                //                 ->body('A project must have at least one member with administrative permissions. Please assign the Admin role to another member before removing this one.')
                //                 ->duration(10000)
                //                 ->danger()
                //                 ->color('danger')
                //                 ->send();
                //             $action->cancel();
                //         }
                //     }
                // ),
            ])
            ->filters([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make()
                        ->authorize('detach', ProjectMember::class),
                ]),
            ])
            ->checkIfRecordIsSelectableUsing(
                fn(Model $record): bool => $record->id === $this->getOwnerRecord()->leader_id ? false : true,
            );
    }

    /**
     * @return array<int|string, string>
     */
    private function getSubstituteOptions(User $record): array
    {
        if (! $record->pivot->site_id || ! $record->can('Manage:Subject')) {
            return [];
        }

        return $this->ownerRecord->members()
            ->wherePivot('site_id', $record->pivot->site_id)
            ->where('users.id', '!=', $record->id)
            ->get()
            ->filter(fn(User $member): bool => $member->can('Manage:Subject'))
            ->pluck('fullname', 'id')
            ->all();
    }

    private function canManageSubstitute(User $record): bool
    {
        return Gate::allows('setSubstitute', [$record->pivot, $this->ownerRecord]);
    }
}
