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

    public function manifest(): BelongsTo
    {
        return $this->belongsTo(Manifest::class);
    }

    public function specimen(): BelongsTo
    {
        return $this->belongsTo(Specimen::class);
    }
    #[\Override]
    protected function casts(): array
    {
        return [
            'received' => 'boolean',
            'receivedTime' => 'datetime',
            'priorSpecimenStatus' => SpecimenStatus::class,
        ];
    }
}
