<?php

namespace App\Models;

use App\Models\Scopes\ProjectScope;
use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

#[ScopedBy([ProjectScope::class])]
class Project extends Model implements HasName
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
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
            ->withPivot(['id', 'role_id', 'site_id', 'substitute_id'])
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

    public function importValueMappings(): HasMany
    {
        return $this->hasMany(ImportValueMapping::class);
    }

    public function getFilamentName(): string
    {
        return $this->title;
    }
}
