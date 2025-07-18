<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Study extends Model
{
    /** @use HasFactory<\Database\Factories\StudyFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function assays(): HasMany
    {
        return $this->hasMany(Assay::class);
    }
}
