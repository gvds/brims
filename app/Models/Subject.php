<?php

namespace App\Models;

use App\Enums\SubjectStatus;
use App\Models\Scopes\SubjectScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[ScopedBy([SubjectScope::class])]
class Subject extends Model
{
    /** @use HasFactory<\Database\Factories\SubjectFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => SubjectStatus::class,
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

    public function previousArm(): BelongsTo
    {
        return $this->belongsTo(Arm::class, 'previous_arm_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'subject_event', 'subject_id', 'event_id')
            ->withPivot('id', 'iteration', 'status', 'labelstatus', 'eventDate', 'minDate', 'maxDate', 'logDate')
            ->withTimestamps();
    }

    public function switchArm(int $arm_id): void
    {
        $this->previous_arm_id = $this->arm_id;
        $this->previousArmBaselineDate = $this->armBaselineDate;
        $this->arm_id = $arm_id;
        $this->armBaselineDate = now();
        $this->save();
        // dd($arm_id);
    }

    public function revertArmSwitch(): void
    {
        $this->arm_id = $this->previous_arm_id;
        $this->armBaselineDate = $this->previousArmBaselineDate;
        $this->previous_arm_id = null;
        $this->previousArmBaselineDate = null;
        $this->save();
    }
}
