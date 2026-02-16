<?php

namespace App\Models;

use App\Models\Scopes\TeamScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ScopedBy(TeamScope::class)]
class Team extends Model
{
    /** @use HasFactory<\Database\Factories\TeamFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function protocols(): HasMany
    {
        return $this->hasMany(Protocol::class);
    }

    public function assayDefinitions(): HasMany
    {
        return $this->hasMany(AssayDefinition::class);
    }
}
