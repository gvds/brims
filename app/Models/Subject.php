<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{
    /** @use HasFactory<\Database\Factories\SubjectFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function arm(): BelongsTo
    {
        return $this->belongsTo(Arm::class);
    }

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'subject_event', 'subject_id', 'event_id')
            ->withPivot('id', 'status', 'created_at', 'updated_at');
    }
}
