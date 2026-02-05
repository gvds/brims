<?php

namespace App\Models;

use App\Enums\SpecimenStatus;
use App\Models\Scopes\SpecimenScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

#[ScopedBy([SpecimenScope::class])]
class Specimen extends Model
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

    public function studies(): BelongsToMany
    {
        return $this->belongsToMany(Study::class, 'study_specimens')
            // ->using(StudySpecimen::class)
            ->withTimestamps();
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

    public function subject(): HasOneThrough
    {
        return $this->hasOneThrough(
            Subject::class,
            SubjectEvent::class,
            'id', // Foreign key on subject_events table
            'id', // Foreign key on subjects table
            'subject_event_id', // Local key on specimens table
            'subject_id' // Local key on subject_events table
        );
    }

    // public function project(): HasOneThrough
    // {
    //     return $this->hasOneThrough(
    //         Project::class,
    //         Specimentype::class,
    //         'id', // Foreign key on specimentypes table
    //         'id', // Foreign key on projects table
    //         'specimenType_id', // Local key on specimens table
    //         'project_id' // Local key on specimentypes table
    //     );
    // }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function manifests(): BelongsToMany
    {
        return $this->belongsToMany(Manifest::class, 'manifest_items')
            ->using(ManifestItem::class)
            ->withPivot(['id', 'priorSpecimenStatus', 'received', 'receivedTime'])
            ->withTimestamps();
    }

    public function setStatus(SpecimenStatus $status): void
    {
        $this->status = $status;
        $this->save();
    }
}
