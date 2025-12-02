<?php

namespace App\Models;

use App\Enums\EventStatus;
use App\Enums\LabelStatus;
use App\Models\Scopes\SubjectEventScope;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\Pivot;

#[ScopedBy([SubjectEventScope::class])]
class SubjectEvent extends Pivot
{

    protected $guarded = ['id'];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class)->with('arm');
    }

    public function arm(): HasOneThrough
    {
        return $this->hasOneThrough(Arm::class, Event::class, 'id', 'id', 'event_id', 'arm_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function specimens(): HasMany
    {
        return $this->hasMany(Specimen::class, 'subject_event_id');
    }

    public function addEventIteration(CarbonImmutable $eventDate): void
    {
        SubjectEvent::create([
            'event_id' => $this->event_id,
            'subject_id' => $this->subject_id,
            'iteration' => $this->iteration + 1,
            'status' => EventStatus::Pending,
            'labelstatus' => LabelStatus::Pending,
            'eventDate' => $eventDate,
            'minDate' => $eventDate->subDays($this->event->offset_ante_window),
            'maxDate' => $eventDate->addDays($this->event->offset_post_window),
        ]);
    }

    public function log($data): void
    {
        $this->update([
            'status' => $data['eventStatus'],
            'labelstatus' => LabelStatus::Generated,
            'logDate' => $data['logDate'],
        ]);
    }
    protected function casts(): array
    {
        return [
            'status' => EventStatus::class,
            'labelstatus' => LabelStatus::class,
        ];
    }
}
