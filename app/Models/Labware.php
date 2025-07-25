<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Labware extends Model
{
    /** @use HasFactory<\Database\Factories\LabwareFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'preregister' => 'boolean',
    ];
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
