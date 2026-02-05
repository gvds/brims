<?php

namespace App\Models;

use App\Enums\SpecimenStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ManifestItem extends Pivot
{
    /** @use HasFactory<\Database\Factories\ManifestItemFactory> */
    use HasFactory;

    protected $table = 'manifest_items';

    protected $guarded = ['id'];

    protected $casts = [
        'received' => 'boolean',
        'receivedTime' => 'datetime',
        'priorSpecimenStatus' => SpecimenStatus::class,
    ];

    public function manifest(): BelongsTo
    {
        return $this->belongsTo(Manifest::class);
    }

    public function specimen(): BelongsTo
    {
        return $this->belongsTo(Specimen::class);
    }
}
