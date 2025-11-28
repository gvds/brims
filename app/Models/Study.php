<?php

namespace App\Models;

use App\Models\Scopes\StudyScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ScopedBy([StudyScope::class])]
class Study extends Model
{
    /** @use HasFactory<\Database\Factories\StudyFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function specimens(): BelongsToMany
    {
        return $this->belongsToMany(Specimen::class, 'study_specimens')
            // ->using(StudySpecimen::class)
            ->withTimestamps();
    }

    public function assays(): HasMany
    {
        return $this->hasMany(Assay::class);
    }
}
