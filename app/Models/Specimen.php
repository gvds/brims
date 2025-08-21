<?php

namespace App\Models;

use App\Enums\SpecimenStatus;
use App\Models\Scopes\SpecimenScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

#[ScopedBy([SpecimenScope::class])]
class Specimen extends Pivot
{
    use HasFactory;

    protected $table = 'specimens';

    protected $guarded = ['id'];

    public function subjectEvent(): BelongsTo
    {
        return $this->belongsTo(SubjectEvent::class, 'subject_event_id');
    }

    public function specimenType(): BelongsTo
    {
        return $this->belongsTo(Specimentype::class, 'specimenType_id');
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    public function parentSpecimen(): BelongsTo
    {
        return $this->belongsTo(Specimen::class, 'parentSpecimen_id');
    }

    public function loggedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'loggedBy_id');
    }

    public function loggedOutBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'loggedOutBy_id');
    }

    public function usedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usedBy_id');
    }

    public function logOut(): void
    {
        $this->status = SpecimenStatus::LoggedOut;
        $this->loggedOutBy()->associate(auth()->user());
        $this->save();
    }

    public function logReturn(): void
    {
        $this->status = SpecimenStatus::InStorage;
        $this->loggedOutBy()->disassociate();
        $this->save();
    }
    protected function casts(): array
    {
        return [
            'status' => SpecimenStatus::class,
        ];
    }
}
