<?php

namespace App\Models;

use App\Enums\SpecimenStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Specimen extends Pivot
{
    protected $table = 'specimens';

    protected $guarded = ['id'];

    protected $casts = [
        'status' => SpecimenStatus::class,
    ];

    public function subjectEvent(): BelongsTo
    {
        return $this->belongsTo(SubjectEvent::class, 'subject_event_id');
    }

    public function specimenType(): BelongsTo
    {
        return $this->belongsTo(Specimentype::class, 'specimen_type_id');
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    public function parentSpecimen(): BelongsTo
    {
        return $this->belongsTo(Specimen::class, 'parentSpecimen_id');
    }
}
