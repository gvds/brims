<?php

namespace App\Filament\Project\Resources\Projects\RelationManagers;

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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class MembersRelationManager extends RelationManager
{
    protected static string $relationship = 'members';

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
    //             TextInput::make('homesite')
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
                    ->icon('heroicon-o-pencil')
                    ->badge()
                    ->placeholder(fn() => new HtmlString(Blade::render('<x-heroicon-o-pencil class="w-4 h-4 inline mr-1" />' . 'None')))
                    ->action(
                        Action::make('selectSubstitute')
                            ->label('Select Substitute')
                            ->icon('heroicon-o-user-plus')
                            ->schema([
                                Select::make('substitute_id')
                                    ->label('Select Substitute')
                                    ->placeholder('Choose a substitute...')
                                    ->options(function (User $record) {
                                        // Get the current user's site from the pivot
                                        $userSiteId = $record->pivot->site_id;

                                        if (!$userSiteId) {
                                            return [];
                                        }

                                        // Get all project members from the same site, excluding the current user
                                        return $this->ownerRecord->members()
                                            ->wherePivot('site_id', $userSiteId)
                                            ->where('users.id', '!=', $record->id)
                                            ->get()
                                            ->pluck('fullname', 'id')
                                            ->toArray();
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->nullable(),
                            ])
                            ->action(function (User $record, array $data): void {
                                // Update the substitute_id in the project_member pivot table
                                $this->ownerRecord->members()
                                    ->updateExistingPivot($record->id, [
                                        'substitute_id' => $data['substitute_id'],
                                    ]);
                            })
                            ->fillForm(fn(User $record): array => [
                                'substitute_id' => $record->pivot->substitute_id,
                            ])
                            ->modalHeading(fn(User $record): string => "Select Substitute for {$record->fullname}")
                            ->modalDescription('Choose a substitute from members of the same project site.')
                            ->modalSubmitActionLabel('Save Substitute')
                            ->modalCancelActionLabel('Cancel')
                            ->authorize('setSubstitute', ProjectMember::class),
                    ),
            ])
            ->headerActions([
                AttachAction::make()
                    ->authorize('attach', ProjectMember::class)
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
}
