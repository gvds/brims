<?php

namespace App\Filament\App\Resources\Teams\RelationManagers;

use App\Models\Project;
use App\Models\Role;
use App\Models\Site;
use App\Services\REDCap;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;
use Filament\Support\Markdown;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class ProjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'projects';

    #[\Override]
    public function isReadOnly(): bool
    {
        return false;
    }

    #[\Override]
    public function form(Schema $schema): Schema
    {
        // return ProjectForm::configure($schema)->extraAttributes(['class' => 'w-full']);
        return $schema
            ->components(Project::formComponents($this->getOwnerRecord()->getKey()))
            ->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            // ->striped()
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                IconColumn::make('redcapProject_id')
                    ->label('REDCap Linked')
                    ->boolean()
                    ->trueIcon('heroicon-o-link')
                    ->trueColor('success')
                    ->alignCenter(),
                TextColumn::make('studyDesign.type')
                    ->label('Study Design')
                    ->searchable(),
                TextColumn::make('leader.fullname')
                    ->searchable(['firstname', 'lastname']),
                TextColumn::make('members_count')
                    ->label('Members')
                    ->counts('members')
                    ->alignCenter(),
                TextColumn::make('submission_date')
                    ->date('Y-m-d')
                    ->sortable(),
                TextColumn::make('programmes.name')
                    ->label('Programmes')
                    ->listWithLineBreaks()
                    ->size(TextSize::ExtraSmall)
                    ->placeholder('--- No Programmes ---'),
                TextColumn::make('public_release_date')
                    ->date('Y-m-d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('active')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('title')
            ->filters([
                //
            ])
            ->recordUrl(
                null
            )
            ->headerActions([
                CreateAction::make()
                    ->createAnother(false)
                    ->after(function (Project $record): void {
                        try {
                            DB::beginTransaction();
                            $site = Site::create([
                                'project_id' => $record->id,
                                'name' => Auth::user()->team->institution->name,
                                'description' => 'Project Creator\'s site',
                            ]);
                            setPermissionsTeamId($record->id);
                            $role = Role::create([
                                'project_id' => $record->id,
                                'name' => 'Admin',
                                'guard_name' => 'web',
                            ]);
                            $role->givePermissionTo(Permission::all());
                            $user = Auth::user();
                            $record->members()->attach(Auth::user(), ['role_id' => $role->id, 'site_id' => $site->id]);
                            $user->syncRoles($role);
                            if ($record->leader_id !== Auth::user()->id) {
                                if ($record->leader->team->institution->name !== $site->name) {
                                    $site = Site::create([
                                        'project_id' => $record->id,
                                        'name' => $record->leader->team->institution->name,
                                        'description' => 'Project Leader\'s site',
                                    ]);
                                }
                                $record->members()->attach($record->leader, ['role_id' => $role->id, 'site_id' => $site->id]);
                                $record->leader->syncRoles($role);
                            }
                            setPermissionsTeamId(null);
                            DB::commit();
                        } catch (\Throwable $th) {
                            DB::rollBack();
                            $record->delete();
                            Notification::make()
                                ->title('Error setting up project!')
                                ->body('There was an error setting up the project. ' . $th->getMessage())
                                ->danger()
                                ->persistent()
                                ->send();
                        }
                    }),
                Action::make('new_redcap_project')
                    ->visible(fn(): bool => Auth::user()->can('create', Project::class))
                    ->modalHeading('Create New REDCap-Linked Project')
                    ->schema(fn(Schema $schema): Schema => $schema
                        ->components([
                            Select::make('redcapProject_id')
                                ->label('Select Redcap Project')
                                ->searchable()
                                ->preload()
                                ->searchDebounce(500)
                                ->options(function () {
                                    $linked_redcap_projects = Project::whereNotNull('redcapProject_id')->pluck('redcapProject_id')->toArray();
                                    try {
                                        return DB::connection('redcap')
                                            ->table('redcap_projects')
                                            ->join('redcap_user_rights', 'redcap_projects.project_id', '=', 'redcap_user_rights.project_id')
                                            ->where('username', Auth::user()->username)
                                            ->where('design', 1)
                                            ->whereNotNull('api_token')
                                            ->whereNotIn('redcap_projects.project_id', $linked_redcap_projects)
                                            ->orderBy('app_title')
                                            ->pluck('app_title', 'redcap_projects.project_id')
                                            ->toArray();
                                    } catch (QueryException $e) {
                                        Notification::make()
                                            ->title('REDCap unavailable')
                                            ->body('Could not connect to the REDCap database. Please try again later.')
                                            ->danger()
                                            ->send();

                                        return [];
                                    }
                                })
                                ->required(),
                            ...Project::formComponents($this->getOwnerRecord()->getKey()),
                        ])
                        ->columns(1))
                    ->action(function (array $data): void {
                        DB::beginTransaction();
                        try {
                            $data['team_id'] = Auth::user()->team_id;
                            $project = Project::create($data);
                            REDCap::setupREDCapProject($project);
                            DB::commit();
                        } catch (\Throwable $th) {
                            DB::rollBack();
                            Notification::make()
                                ->title('Error creating project!')
                                ->body('There was an error creating the project. ' . $th->getMessage())
                                ->danger()
                                ->persistent()
                                ->send();
                        }
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()
                    ->after(function (Project $record): void {
                        $projectAdminRole = $record->roles()->where('name', 'Admin')->first();
                        if ($record->members()->where('user_id', $record->leader_id)->count() == 0) {
                            $record->members()->attach($record->leader_id, ['role_id' => $projectAdminRole->id]);
                        } else {
                            $record->members()->updateExistingPivot($record->leader_id, ['role_id' => $projectAdminRole->id]);
                        }
                    }),
                DeleteAction::make()
                    ->modalHeading(fn(Project $record): Markdown => Markdown::inline("Delete Project<br><br>*$record->title*<br><br>"))
                    ->modalDescription(Markdown::inline('**All data pertaining to this project will be deleted.<br><br>Are you sure you want to proceed?**'))
                    ->after(fn(Project $record) => Role::where('project_id', $record->id)->delete()),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
