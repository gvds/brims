<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StorageAllocation extends Model
{
    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'storageDestination' => 'enum:StorageDestination',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function storageLogs(): HasMany
    {
        return $this->hasMany(StorageLog::class);
    }
}
