<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PhysicalUnit extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'available' => 'boolean',
    ];

    /**
     * @return BelongsTo <UnitDefinition, PhysicalUnit>
     */
    public function unitDefinition(): BelongsTo
    {
        return $this->belongsTo(UnitDefinition::class);
    }

    /**
     * @return BelongsTo <User, PhysicalUnit>
     */
    public function administrator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return HasMany <VirtualUnit>
     */
    public function virtualUnits(): HasMany
    {
        return $this->hasMany(VirtualUnit::class);
    }
}
