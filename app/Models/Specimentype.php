<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Specimentype extends Model
{
    /** @use HasFactory<\Database\Factories\SpecimentypeFactory> */
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

    // public function virtualUnits()
    // {
    //     return $this->hasMany(virtualUnit::class, 'storageSampleType', 'storageSampleType');
    //     // return $this->hasMany(virtualUnit::class, 'storageSampleType', 'storageSampleType')->where('project_id', $this->project_id);
    // }
    protected function casts(): array
    {
        return [
            'transferDestinations' => 'json',
        ];
    }
}
