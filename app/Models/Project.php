<?php

namespace App\Models;

use App\Enums\SystemRoles;
use App\Models\Scopes\ProjectScope;
use Database\Factories\ProjectFactory;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Models\Contracts\HasName;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

#[ScopedBy([ProjectScope::class])]
class Project extends Model implements HasName
{
    /** @use HasFactory<ProjectFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function labelSpecification(): BelongsTo
    {
        return $this->belongsTo(LabelSpecification::class, 'label_format', 'format');
    }

    public function arms(): HasMany
    {
        return $this->hasMany(Arm::class);
    }

    public function events(): HasManyThrough
    {
        return $this->hasManyThrough(Event::class, Arm::class);
    }

    public function sites(): HasMany
    {
        return $this->hasMany(Site::class);
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }

    public function studies(): HasMany
    {
        return $this->hasMany(Study::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_member')
            ->using(ProjectMember::class)
            ->withPivot(['id', 'role_id', 'site_id', 'substitute_id', 'redcap_token'])
            ->withTimestamps();
    }

    public function specimentypes(): HasMany
    {
        return $this->hasMany(Specimentype::class);
    }

    public function specimens(): HasMany
    {
        return $this->hasMany(Specimen::class);
    }

    public function labware(): HasMany
    {
        return $this->hasMany(Labware::class);
    }

    public function publications(): HasMany
    {
        return $this->hasMany(Publication::class);
    }

    public function studyDesign(): BelongsTo
    {
        return $this->belongsTo(StudyDesign::class);
    }

    public function programmes(): BelongsToMany
    {
        return $this->belongsToMany(Programme::class)
            ->withTimestamps();
    }

    public function admins(): BelongsToMany
    {
        return $this->members()
            ->wherePivot('role_id', Role::where('name', 'Admin')->first()?->id);
    }

    public function getFilamentName(): string
    {
        return $this->title;
    }

    /** @return HasMany<Role, self> */
    public function roles(): HasMany
    {
        return $this->hasMany(Role::class);
    }

    /**
     * @return array<int, Component>
     */
    public static function formComponents(int|string $teamId): array
    {
        return [
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
                    Select::make('leader_id')
                        ->relationship(
                            name: 'leader',
                            modifyQueryUsing: fn (Builder $query) => $query->where(
                                fn (Builder $query) => $query->where('team_id', $teamId)
                                    ->orWhereIn('system_role', [SystemRoles::SysAdmin->value, SystemRoles::SuperAdmin->value])
                            )
                        )
                        ->getOptionLabelFromRecordUsing(
                            fn ($record) => $record->fullname
                        )
                        ->required(),
                ]),
            Select::make('study_design_id')
                ->relationship(name: 'studyDesign', titleAttribute: 'type')
                ->required(),
            Select::make('programmes')
                ->multiple()
                ->relationship(name: 'programmes', titleAttribute: 'name')
                ->getOptionLabelFromRecordUsing(
                    fn (Programme $record): string => "{$record->name} ({$record->funder}) - {$record->pi->fullname}"
                )
                ->preload(),
            Textarea::make('description')
                ->default(null)
                ->columnSpanFull(),
            Fieldset::make('Subject ID')
                ->schema([
                    TextInput::make('subjectID_prefix')
                        ->label('Prefix')
                        ->helperText('Between 2 and 10 uppercase characters or digits, starting with an uppercase letter')
                        ->required()
                        ->regex('/^[A-Z][A-Z0-9]{1,9}$/'),
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
                    TextInput::make('storageDesignation')
                        ->label('Storage Designation')
                        ->required()
                        ->maxLength(40),
                    Select::make('label_format')
                        ->label('Label Format')
                        ->relationship('labelSpecification', 'format')
                        ->required(),
                ]),
            Grid::make(2)
                ->schema([
                    DatePicker::make('submission_date'),
                    DatePicker::make('public_release_date')
                        ->visibleOn(['view', 'edit']),
                ]),
        ];
    }
}
