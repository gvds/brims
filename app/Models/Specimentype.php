<?php

namespace App\Models;

use Database\Factories\SpecimentypeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Specimentype extends Model
{
    /** @use HasFactory<SpecimentypeFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function specimens(): HasMany
    {
        return $this->hasMany(Specimen::class);
    }

    public function labware(): BelongsTo
    {
        return $this->belongsTo(Labware::class);
    }

    public function parentSpecimenType(): BelongsTo
    {
        return $this->belongsTo(Specimentype::class, 'parentSpecimenType_id', 'id');
    }

    protected function casts(): array
    {
        return [
            'transferDestinations' => 'json',
            'defaultVolume' => 'float',
        ];
    }
}
