<?php

declare(strict_types=1);

use App\Filament\Project\Resources\Manifests\Pages\ListManifests;
use App\Enums\ManifestStatus;
use App\Enums\SystemRoles;
use App\Models\Manifest;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Session;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->team = Team::factory()->create();
    $this->superAdmin = User::factory()->create([
        'system_role' => SystemRoles::SuperAdmin,
        'team_id' => $this->team->id,
        'team_role' => 'Admin',
    ]);

    $this->project = Project::factory()
        ->for($this->team)
        ->hasSites(2)
        ->create([
            'leader_id' => $this->superAdmin->id,
        ]);

    $this->sourceSite = $this->project->sites->first();
    $this->destinationSite = $this->project->sites->last();

    actingAs($this->superAdmin);
    Session::put('currentProject', $this->project);
    Filament::setCurrentPanel('project');
    Filament::setTenant($this->project);
    Filament::bootCurrentPanel();
});

it('does not filter manifest list tabs for superadmins when they are not project members', function (): void {
    $manifest = Manifest::factory()->create([
        'project_id' => $this->project->id,
        'user_id' => $this->superAdmin->id,
        'sourceSite_id' => $this->sourceSite->id,
        'destinationSite_id' => $this->destinationSite->id,
        'specimenTypes' => [],
        'status' => ManifestStatus::Shipped,
    ]);

    actingAs($this->superAdmin);

    livewire(ListManifests::class)
        ->assertOk()
        ->assertCanSeeTableRecords([$manifest]);
});
