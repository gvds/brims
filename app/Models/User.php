<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\SystemRoles;
use App\Enums\TeamRoles;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthentication;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthenticationRecovery;
use Filament\Models\Contracts\HasName;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasName, HasAppAuthentication, HasAppAuthenticationRecovery, HasTenants
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = ['id'];

    protected $appends = ['fullname'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'app_authentication_secret',
        'app_authentication_recovery_codes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'app_authentication_secret' => 'encrypted',
            'app_authentication_recovery_codes' => 'encrypted:array',
            'system_role' => SystemRoles::class,
            'active' => 'boolean',
        ];
    }


    public function canAccessPanel(Panel $panel): bool
    {
        // if ($panel->getId() === 'project' && session()->missing('currentProject')) {
        if ($panel->getId() === 'project') {
            // return false;
            return session()->has('currentProject');
        }

        if ($panel->getId() === 'admin') {
            return in_array($this->system_role, [SystemRoles::SysAdmin, SystemRoles::SuperAdmin]);
        }

        return true;
    }

    public function getAppAuthenticationSecret(): ?string
    {
        // This method should return the user's saved app authentication secret.

        return $this->app_authentication_secret;
    }

    public function saveAppAuthenticationSecret(?string $secret): void
    {
        // This method should save the user's app authentication secret.

        $this->app_authentication_secret = $secret;
        $this->save();
    }

    public function getAppAuthenticationHolderName(): string
    {
        // In a user's authentication app, each account can be represented by a "holder name".
        // If the user has multiple accounts in your app, it might be a good idea to use
        // their email address as then they are still uniquely identifiable.

        return $this->email;
    }

    /**
     * @return ?array<string>
     */
    public function getAppAuthenticationRecoveryCodes(): ?array
    {
        // This method should return the user's saved app authentication recovery codes.

        return $this->app_authentication_recovery_codes;
    }

    /**
     * @param  array<string> | null  $codes
     */
    public function saveAppAuthenticationRecoveryCodes(?array $codes): void
    {
        // This method should save the user's app authentication recovery codes.

        $this->app_authentication_recovery_codes = $codes;
        $this->save();
    }

    public function getTenants(Panel $panel): Collection
    {
        return $this->projects;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->projects()->whereKey($tenant)->exists();
    }

    protected function fullname(): Attribute
    {
        return new Attribute(
            get: fn(): string => $this->firstname . ' ' . $this->lastname,
        );
    }

    public function getFilamentName(): string
    {
        return $this->fullname;
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    protected function isTeamLeader(): Attribute
    {
        return new Attribute(
            get: fn(): bool => $this->team && $this->id === $this->team->leader_id,
        );
    }

    protected function isTeamAdmin(): Attribute
    {
        return new Attribute(
            get: fn(): bool => $this->team && $this->team_role === TeamRoles::Admin->value,
        );
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_member')->withPivot(['id', 'role_id', 'site_id'])->withTimestamps();
    }

    public function projectSite(): HasOneThrough
    {
        return $this->hasOneThrough(
            Site::class,
            ProjectMember::class,
            'user_id',
            'id',
            'id',
            'site_id'
        );
    }

    public function projectSubstitute(): HasOneThrough
    {
        return $this->hasOneThrough(
            User::class,
            ProjectMember::class,
            'user_id',
            'id',
            'id',
            'substitute_id'
        );
    }

    public function substitutees(): HasManyThrough
    {
        return $this->hasManyThrough(
            User::class,
            ProjectMember::class,
            'substitute_id', // Foreign key on ProjectMember table
            'id',            // Foreign key on User table
            'id',            // Local key on User table (this user)
            'user_id'       // Local key on ProjectMember table
        );
    }
}
