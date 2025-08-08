<?php

namespace App\Models;

use App\Enums\EventStatus;
use App\Enums\LabelStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SubjectEvent extends Pivot
{
    protected $guarded = ['id'];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    protected $casts = [
        'status' => EventStatus::class,
        'labelstatus' => LabelStatus::class,
    ];

    // public function event(): BelongsTo
    // {
    //     return $this->belongsTo(Event::class)->with('arm');
    // }

    // public function subject(): BelongsTo
    // {
    //     return $this->belongsTo(Subject::class);
    // }
}
