<?php

namespace App\Models;

use App\Enums\EventStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function arm(): BelongsTo
    {
        return $this->belongsTo(Arm::class);
    }

    public function project(): HasOneThrough
    {
        return $this->hasOneThrough(
            Project::class,
            Arm::class,
            'id', // Foreign key on arms table
            'id', // Foreign key on projects table
            'arm_id', // Local key on events table
            'project_id' // Local key on arms table
        );
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
            'autolog' => 'boolean',
        ];
    }
}
