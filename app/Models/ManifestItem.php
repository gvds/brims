<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManifestItem extends Model
{
    /** @use HasFactory<\Database\Factories\ManifestItemFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function manifest(): BelongsTo
    {
        return $this->belongsTo(Manifest::class);
    }
}
