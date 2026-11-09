<?php

namespace App\Models;

use Database\Factories\LabwareFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Labware extends Model
{
    /** @use HasFactory<LabwareFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    #[\Override]
    protected function casts(): array
    {
        return [
            'preregister' => 'boolean',
        ];
    }

    public function specimenTypes(): HasMany
    {
        return $this->hasMany(Specimentype::class);
    }

    public function specimens(): HasManyThrough
    {
        return $this->hasManyThrough(Specimen::class, Specimentype::class, 'labware_id', 'specimenType_id');
    }
}
