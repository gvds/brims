<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Arm extends Model
{
    /** @use HasFactory<\Database\Factories\ArmFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'manual_enrol' => 'boolean',
        'switcharms' => 'array',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    // public function switcharms(): HasMany
    // {
    //     return $this->hasMany(Arm::class, 'switcharms', 'id');
    // }
}
