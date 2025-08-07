<?php

namespace App\Models;

use App\Enums\SubjectStatus;
use App\Models\Scopes\SubjectScope;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

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
    // public function events(): BelongsToMany
    // {
    //     return $this->belongsToMany(Event::class, 'subject_event', 'subject_id', 'event_id')
    //         ->withPivot('id', 'iteration', 'status', 'labelstatus', 'eventDate', 'minDate', 'maxDate', 'logDate')
    //         ->withTimestamps();
    // }
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'subject_event', 'subject_id', 'event_id')
            ->using(SubjectEvent::class)
            // ->withPivot('id', 'iteration', 'status', 'labelstatus', 'eventDate', 'minDate', 'maxDate', 'logDate')
            ->withTimestamps();
    }

    public function subjectEvents(): HasMany
    {
        return $this->hasMany(SubjectEvent::class);
        // ->join('events', 'subject_event.event_id', '=', 'events.id')
        // ->join('arms', 'events.arm_id', '=', 'arms.id')
        // ->orderBy('arms.arm_num', 'asc')
        // ->orderBy('event_order', 'asc')
        // ->orderBy('iteration', 'asc');
    }

    public function switchArm(int $arm_id, string $armBaselineDate): void
    {
        try {
            DB::beginTransaction();

            SubjectEvent::where('subject_id', $this->id)->whereRelation('event', 'arm_id', $this->arm_id)->whereIn('status', [0, 1, 2])->update(['status' => 6]);

            $this->previous_arm_id = $this->arm_id;
            $this->previousArmBaselineDate = $this->armBaselineDate;
            $this->arm_id = $arm_id;
            $this->armBaselineDate = $armBaselineDate;
            $this->save();

            $armBaselineDate = new CarbonImmutable($armBaselineDate);
            $newevents = Event::where('arm_id', $arm_id)->get();
            $newevents->each(fn($event) => $this->events()->attach(
                $event,
                [
                    'eventDate' => $armBaselineDate->addDays($event->offset),
                    'minDate' => $armBaselineDate->addDays($event->offset - $event->offset_ante_window),
                    'maxDate' => $armBaselineDate->addDays($event->offset + $event->offset_post_window),
                    'iteration' => 1,
                    'status' => 0,
                ]
            ));

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function revertArmSwitch(): void
    {
        try {
            DB::beginTransaction();

            SubjectEvent::where('subject_id', $this->id)->whereRelation('event', 'arm_id', $this->arm_id)->delete();

            $this->arm_id = $this->previous_arm_id;
            $this->armBaselineDate = $this->previousArmBaselineDate;
            $this->previous_arm_id = null;
            $this->previousArmBaselineDate = null;
            $this->save();

            SubjectEvent::where('subject_id', $this->id)->whereRelation('event', 'arm_id', $this->arm_id)->where('status', 6)->update(['status' => 0]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
