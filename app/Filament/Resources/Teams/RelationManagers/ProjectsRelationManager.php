<?php

namespace App\Filament\Resources\Teams\RelationManagers;

use App\Models\Project;
use App\Models\Role;
use App\Models\Site;
use Dom\Text;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'projects';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        // return ProjectForm::configure($schema)->extraAttributes(['class' => 'w-full']);
        return $schema
            ->components([
                TextInput::make('title')
                    ->autocomplete(false)
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(100)
                    ->minLength(5),
                Grid::make(2)
                    ->schema([
                        TextInput::make('identifier')
                            ->autocomplete(false)
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('storageProjectName')
                            ->label('Storage Project Name')
                            ->required()
                            ->maxLength(40),
                    ]),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('leader_id')
                    ->relationship(
                        name: 'leader',
                        modifyQueryUsing: fn(Builder $query) => $query->where('team_id', Auth::user()->team_id)
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn($record) => $record->fullname
                    )
                    ->required(),
                Fieldset::make("Subject ID")
                    ->schema([
                        TextInput::make('subjectID_prefix')
                            ->label('Prefix')
                            ->hint('Between 2 and 10 uppercase characters')
                            ->required()
                            ->maxLength(10)
                            ->minLength(2)
                            ->regex('/^[A-Z]{2,10}$/'),
                        TextInput::make('subjectID_digits')
                            ->label('Digits')
                            ->numeric()
                            ->required()
                            ->minValue(2)
                            ->maxValue(8)
                            ->hint('The number of digits in a subject ID'),
                    ]),
                Grid::make(2)
                    ->schema([
                        DatePicker::make('submission_date'),
                        DatePicker::make('public_release_date')
                            ->visibleOn(['view', 'edit']),
                    ]),
            ])
            ->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            // ->striped()
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                // ->action(function (Project $record) {
                //     if (auth()->user()->can('view', $record)) {
                //         session(['currentProject' => $record]);
                //         setPermissionsTeamId($record->id);
                //         return to_route('filament.project.pages.dashboard', $parameters = ['tenant' => $record->id]);
                //     }
                // })
                // ->extraAttributes(['class' => 'text-sky-800 dark:text-sky-500 hover:invert']),
                TextColumn::make('team.name')
                    ->searchable(),
                TextColumn::make('leader.fullname')
                    ->searchable(['firstname', 'lastname']),
                TextColumn::make('submission_date')
                    ->date('Y-m-d')
                    ->sortable(),
                TextColumn::make('public_release_date')
                    ->date('Y-m-d')
                    ->sortable(),
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
                    ->after(function (Project $record): void {
                        try {
                            DB::beginTransaction();
                            $site = Site::create([
                                'project_id' => $record->id,
                                'name' => Auth::user()->homesite,
                                'description' => 'Project Creator\'s site',
                            ]);
                            $role = Role::create([
                                'project_id' => $record->id,
                                'name' => 'Admin',
                                'guard_name' => 'web',
                            ]);
                            $record->members()->attach(Auth::user(), ['role_id' => $role->id, 'site_id' => $site->id]);
                            if ($record->leader_id !== Auth::user()->id) {
                                if ($record->leader->homesite !== $site->name) {
                                    $site = Site::create([
                                        'project_id' => $record->id,
                                        'name' => $record->leader->homesite,
                                        'description' => 'Project Leader\'s site',
                                    ]);
                                }
                                $record->members()->attach($record->leader, ['role_id' => $role->id, 'site_id' => $site->id]);
                            }
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
                    ->schema([
                        Select::make('redcap_project_id')
                            ->label('Redcap Project')
                            ->options(function () {
                                $query = "select app_title, project_id from redcap_projects";
                                $linked_redcap_projects = Project::whereNot('redcapProject_id', 'null')->pluck('redcapProject_id')->toArray();
                                if (count($linked_redcap_projects) > 0) {
                                    $query .= " where project_id not in (" . implode(",", $linked_redcap_projects) . ")";
                                }
                                $query .= " order by app_title";
                                $redcap_projects = DB::connection('redcap')
                                    ->select($query);
                                return collect($redcap_projects)->pluck('app_title', 'project_id')->toArray();
                            })
                            ->required(),
                        TextInput::make('title')
                            ->autocomplete(false)
                            ->required()
                            ->unique()
                            ->maxLength(100)
                            ->minLength(5),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('identifier')
                                    ->autocomplete(false)
                                    ->required()
                                    ->unique(ignoreRecord: true),
                                TextInput::make('storageProjectName')
                                    ->label('Storage Project Name')
                                    ->required()
                                    ->maxLength(40),
                            ]),
                        Textarea::make('description')
                            ->default(null)
                            ->columnSpanFull(),
                        Fieldset::make("Subject ID")
                            ->schema([
                                TextInput::make('subjectID_prefix')
                                    ->label('Prefix')
                                    ->hint('Between 2 and 10 uppercase characters')
                                    ->required()
                                    ->maxLength(10)
                                    ->minLength(2)
                                    ->regex('/^[A-Z]{2,10}$/'),
                                TextInput::make('subjectID_digits')
                                    ->label('Digits')
                                    ->numeric()
                                    ->required()
                                    ->minValue(2)
                                    ->maxValue(8)
                                    ->hint('The number of digits in a subject ID'),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Select::make('leader_id')
                                    ->relationship(
                                        name: 'leader',
                                        modifyQueryUsing: fn(Builder $query) => $query->where('team_id', Auth::user()->team_id)
                                    )
                                    ->getOptionLabelFromRecordUsing(
                                        fn($record) => $record->fullname
                                    )
                                    ->required(),
                                DatePicker::make('submission_date'),
                            ]),
                    ])
                    ->action(fn() => null)
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
