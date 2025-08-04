<?php

namespace App\Models;

use App\Enums\EventStatus;
use App\Enums\LabelStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SubjectEvent extends Pivot
{
    protected $guarded = ['id'];

    protected $casts = [
        'status' => EventStatus::class,
        'labelstatus' => LabelStatus::class,
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
