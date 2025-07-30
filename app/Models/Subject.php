<?php

namespace App\Models;

use App\Enums\SubjectStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{
    /** @use HasFactory<\Database\Factories\SubjectFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'subject_status' => SubjectStatus::class,
        'address' => 'json',
    ];

    protected function fullname(): Attribute
    {
        return new Attribute(
            get: fn() => $this->firstname . ' ' . $this->lastname,
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function arm(): BelongsTo
    {
        return $this->belongsTo(Arm::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'subject_event', 'subject_id', 'event_id')
            ->withPivot('id', 'iteration', 'eventstatus', 'labelstatus', 'eventDate', 'minDate', 'maxDate', 'logDate');
    }
}
