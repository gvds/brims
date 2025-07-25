<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'repeatable' => 'boolean',
        'active' => 'boolean',
    ];

    public function arm()
    {
        return $this->belongsTo(Arm::class);
    }
}
