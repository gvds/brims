<?php

namespace App\Models;

use App\Enums\EventStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function arm(): BelongsTo
    {
        return $this->belongsTo(Arm::class);
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'subject_event', 'event_id', 'subject_id')
            ->withPivot('id', 'iteration', 'status', 'labelstatus', 'eventDate', 'minDate', 'maxDate', 'logDate')
            ->withTimestamps();
    }

    // public function log($data): void
    // {
    //     $this->pivot->update([
    //         'status' => EventStatus::Logged,
    //         'logDate' => $data['logDate'],
    //     ]);
    // }
    protected function casts(): array
    {
        return [
            'repeatable' => 'boolean',
            'active' => 'boolean',
        ];
    }
}
