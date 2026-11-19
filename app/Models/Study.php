<?php

namespace App\Models;

use App\Enums\StudyRoles;
use App\Enums\TeamRoles;
use App\Models\Scopes\StudyScope;
use Database\Factories\StudyFactory;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

#[ScopedBy([StudyScope::class])]
class Study extends Model
{
    /** @use HasFactory<StudyFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function project(): BelongsToMany
    {
        return $this->belongsToMany(Project::class)
            ->withTimestamps();
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'study_member')
            ->using(StudyMembers::class)
            ->withPivot(['role'])
            ->withTimestamps();
    }

    /**
     * Users who may be members of a study: admins of the owning team and members of the given projects.
     *
     * @param  array<int, int|string>  $projectIds
     * @return Builder<User>
     */
    public static function eligibleMembersQuery(?int $teamId, array $projectIds): Builder
    {
        return User::query()
            ->withoutGlobalScopes()
            ->where(function (Builder $query) use ($teamId, $projectIds): void {
                $query->where(function (Builder $query) use ($teamId): void {
                    $query->where('users.team_id', $teamId)
                        ->where('users.team_role', TeamRoles::Admin->value);
                })->orWhereIn('users.id', DB::table('project_member')
                    ->whereIn('project_id', $projectIds)
                    ->select('user_id'));
            });
    }

    /**
     * @return Builder<User>
     */
    public function eligibleMembers(): Builder
    {
        return static::eligibleMembersQuery(
            $this->team_id,
            $this->project()->pluck('projects.id')->all(),
        );
    }

    /**
     * Current members who would no longer be eligible if the study were linked to only the given projects.
     *
     * @param  array<int, int|string>  $projectIds
     * @return Collection<int, User>
     */
    public function membersIneligibleWithProjects(array $projectIds): Collection
    {
        return $this->members()
            ->whereNotIn('users.id', static::eligibleMembersQuery($this->team_id, $projectIds)->select('users.id'))
            ->get();
    }

    public function isLead(User $user): bool
    {
        return $this->members()
            ->wherePivot('role', StudyRoles::Lead->value)
            ->whereKey($user->getKey())
            ->exists();
    }

    public function isOnlyLead(User $user): bool
    {
        $leadIds = $this->members()
            ->wherePivot('role', StudyRoles::Lead->value)
            ->pluck('users.id');

        return $leadIds->count() === 1 && $leadIds->first() === $user->getKey();
    }

    /**
     * The given project ids that have at least one specimen attached to this study.
     *
     * @param  array<int, int|string>  $projectIds
     * @return array<int, int>
     */
    public function projectIdsWithSpecimens(array $projectIds): array
    {
        return $this->specimens()
            ->withoutGlobalScopes()
            ->whereIn('specimens.project_id', $projectIds)
            ->distinct()
            ->pluck('specimens.project_id')
            ->all();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function specimens(): BelongsToMany
    {
        return $this->belongsToMany(Specimen::class, 'study_specimens')
            // ->using(StudySpecimen::class)
            ->withTimestamps();
    }

    public function assays(): HasMany
    {
        return $this->hasMany(Assay::class);
    }

    #[\Override]
    protected function casts(): array
    {
        return [
            'submission_date' => 'date',
            'public_release_date' => 'date',
            'locked' => 'boolean',
        ];
    }
}
