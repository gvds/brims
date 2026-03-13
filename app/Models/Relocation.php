<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Relocation extends Model
{
    protected $guarded = ['id'];

    public function StorageConsolidation(): BelongsTo
    {
        return $this->belongsTo(StorageConsolidation::class);
    }

    public function sourcelocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'source_location_id', 'id');
    }

    public function destinationlocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'destination_location_id', 'id');
    }
}
