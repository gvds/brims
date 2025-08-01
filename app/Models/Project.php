<?php

namespace App\Models;

use App\Models\Scopes\ProjectScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

#[ScopedBy([ProjectScope::class])]
class Project extends Model
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
        return $this->belongsToMany(User::class, 'project_member')->withPivot(['id', 'role', 'site_id'])->withTimestamps();
    }

    public function specimentypes(): HasMany
    {
        return $this->hasMany(Specimentype::class);
    }

    public function labware(): HasMany
    {
        return $this->hasMany(Labware::class);
    }

    public function publications(): HasMany
    {
        return $this->hasMany(Publication::class);
    }
}
