<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StorageLog extends Model
{
    protected $guarded = [
        'id',
    ];

    public function storageAllocation()
    {
        return $this->belongsTo(StorageAllocation::class);
    }

    public function specimen(): BelongsTo
    {
        return $this->belongsTo(Specimen::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function specimenType(): BelongsTo
    {
        return $this->belongsTo(Specimentype::class, 'specimentype_id');
    }
}
