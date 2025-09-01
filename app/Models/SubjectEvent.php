<?php

namespace App\Models;

use App\Enums\EventStatus;
use App\Enums\LabelStatus;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class)->with('arm');
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
        $subjectEvent = new SubjectEvent([
            'event_id' => $this->event_id,
            'subject_id' => $this->subject_id,
            'iteration' => $this->iteration + 1,
            'status' => EventStatus::Pending,
            'labelstatus' => LabelStatus::Pending,
            'eventDate' => $eventDate,
            'minDate' => $eventDate->subDays($this->event->offset_ante_window),
            'maxDate' => $eventDate->addDays($this->event->offset_post_window),
        ]);
        $subjectEvent->save();
    }

    public function log($data): void
    {
        $this->update([
            'status' => EventStatus::Logged,
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
