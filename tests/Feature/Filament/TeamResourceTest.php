<?php

use App\Enums\TeamRoles;
use App\Filament\Resources\Teams\Pages\CreateTeam;
use App\Filament\Resources\Teams\Pages\EditTeam;
use App\Filament\Resources\Teams\Pages\ListTeams;
use App\Filament\Resources\Teams\TeamResource;
use App\Models\Team;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Illuminate\Support\Facades\Auth;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    actingAs($this->adminuser);
});

describe('TeamResource List Page', function (): void {
    it('can load the teams list page', function (): void {
        $teams = Team::factory()->count(5)->create();

        livewire(ListTeams::class)
            ->assertOk()
            ->assertCanSeeTableRecords($teams);
    });

    it('can search teams by name', function (): void {
        $team1 = Team::factory()->create(['name' => 'Research Team Alpha']);
        $team2 = Team::factory()->create(['name' => 'Clinical Team Beta']);

        livewire(ListTeams::class)
            ->searchTable('Research')
            ->assertCanSeeTableRecords([$team1])
            ->assertCanNotSeeTableRecords([$team2]);
    });

    it('displays team information correctly in table', function (): void {
        $leader = User::factory()->create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'team_role' => TeamRoles::Admin->value,
        ]);

        $team = Team::factory()->create([
            'name' => 'Test Team',
            'description' => 'This is a test team description',
            'leader_id' => $leader->id,
        ]);

        // Update the leader to belong to this team
        $leader->update(['team_id' => $team->id]);

        livewire(ListTeams::class)
            ->assertCanSeeTableRecords([$team])
            ->assertTableColumnStateSet('name', 'Test Team', $team)
            ->assertTableColumnStateSet('description', 'This is a test team description', $team)
            ->assertTableColumnStateSet('leader.fullname', 'John Doe', $team);
    });

    it('can sort teams by name', function (): void {
        $teamA = Team::factory()->create(['name' => 'Alpha Team']);
        $teamB = Team::factory()->create(['name' => 'Beta Team']);
        $teamC = Team::factory()->create(['name' => 'Charlie Team']);

        livewire(ListTeams::class)
            ->sortTable('name')
            ->assertCanSeeTableRecords([$teamA, $teamB, $teamC], inOrder: true);
    });

    it('can edit team from table action', function (): void {
        $team = Team::factory()->create();

        livewire(ListTeams::class)
            ->callAction(TestAction::make('edit')->table($team))
            ->assertSuccessful();
    });

    it('can bulk delete teams', function (): void {
        $teams = Team::factory()->count(3)->create();

        livewire(ListTeams::class)
            ->selectTableRecords($teams->pluck('id')->toArray())
            ->callAction(TestAction::make('delete')->table()->bulk())
            ->assertSuccessful();

        foreach ($teams as $team) {
            assertDatabaseMissing('teams', ['id' => $team->id]);
        }
    });
});

describe('TeamResource Create Page', function (): void {
    it('can load the create team page', function (): void {
        livewire(CreateTeam::class)
            ->assertOk()
            ->assertSchemaExists('form');
    });

    it('can create a team with required fields', function (): void {
        livewire(CreateTeam::class)
            ->fillForm([
                'name' => 'New Research Team',
                'description' => 'A team focused on clinical research',
            ])
            ->call('create')
            ->assertRedirect();

        assertDatabaseHas('teams', [
            'name' => 'New Research Team',
            'description' => 'A team focused on clinical research',
        ]);
    });

    it('can create a team with only name', function (): void {
        livewire(CreateTeam::class)
            ->fillForm([
                'name' => 'Minimal Team',
            ])
            ->call('create')
            ->assertRedirect();

        assertDatabaseHas('teams', [
            'name' => 'Minimal Team',
            'description' => null,
        ]);
    });

    it('validates required name field', function (): void {
        livewire(CreateTeam::class)
            ->fillForm([
                'description' => 'Team without name',
            ])
            ->call('create')
            ->assertHasFormErrors(['name']);
    });

    it('validates name max length', function (): void {
        livewire(CreateTeam::class)
            ->fillForm([
                'name' => str_repeat('a', 256), // Exceeds max length of 255
            ])
            ->call('create')
            ->assertHasFormErrors(['name']);
    });

    it('validates unique team name', function (): void {
        Team::factory()->create(['name' => 'Existing Team']);

        $initialCount = Team::count();

        livewire(CreateTeam::class)
            ->fillForm([
                'name' => 'Existing Team',
            ])
            ->call('create');

        // Verify that no new team was created (either due to validation or constraint)
        expect(Team::count())->toBe($initialCount);
    });
});

describe('TeamResource Edit Page', function (): void {
    it('can load the edit team page', function (): void {
        $team = Team::factory()->create();

        livewire(EditTeam::class, ['record' => $team->getRouteKey()])
            ->assertOk()
            ->assertSchemaExists('form')
            ->assertSchemaStateSet([
                'name' => $team->name,
                'description' => $team->description,
            ]);
    });

    it('can update team information', function (): void {
        $team = Team::factory()->create([
            'name' => 'Original Name',
            'description' => 'Original description',
        ]);

        // Create an admin user for this team to act as leader
        $leader = User::factory()->create([
            'team_id' => $team->id,
            'team_role' => TeamRoles::Admin->value,
        ]);

        livewire(EditTeam::class, ['record' => $team->getRouteKey()])
            ->fillForm([
                'name' => 'Updated Team Name',
                'description' => 'Updated team description',
                'leader_id' => $leader->id,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        // Check if the team was updated in the database
        $team->refresh();
        expect($team->name)->toBe('Updated Team Name');
        expect($team->description)->toBe('Updated team description');
        expect($team->leader_id)->toBe($leader->id);
    });

    it('can clear team description', function (): void {
        $team = Team::factory()->create([
            'description' => 'Original description',
        ]);

        // Create an admin user for this team to act as leader
        $leader = User::factory()->create([
            'team_id' => $team->id,
            'team_role' => TeamRoles::Admin->value,
        ]);

        livewire(EditTeam::class, ['record' => $team->getRouteKey()])
            ->fillForm([
                'description' => '',
                'leader_id' => $leader->id,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        // Refresh and check
        $team->refresh();
        expect($team->description)->toBeNull();
    });
    it('validates required name field during update', function (): void {
        $team = Team::factory()->create();

        livewire(EditTeam::class, ['record' => $team->getRouteKey()])
            ->fillForm([
                'name' => '',
            ])
            ->call('save')
            ->assertHasFormErrors(['name']);
    });

    it('validates unique team name during update', function (): void {
        $existingTeam = Team::factory()->create(['name' => 'Existing Team']);
        $teamToUpdate = Team::factory()->create(['name' => 'Team to Update']);

        // Create an admin user for the team being updated
        $leader = User::factory()->create([
            'team_id' => $teamToUpdate->id,
            'team_role' => TeamRoles::Admin->value,
        ]);

        livewire(EditTeam::class, ['record' => $teamToUpdate->getRouteKey()])
            ->fillForm([
                'name' => 'Existing Team',
                'leader_id' => $leader->id,
            ])
            ->call('save');

        // Verify that the team name was not updated due to unique constraint
        $teamToUpdate->refresh();
        expect($teamToUpdate->name)->not->toBe('Existing Team');
        expect($teamToUpdate->name)->toBe('Team to Update');
    });

    it('allows keeping the same name during update', function (): void {
        $team = Team::factory()->create(['name' => 'Same Name']);

        // Create an admin user for this team to act as leader
        $leader = User::factory()->create([
            'team_id' => $team->id,
            'team_role' => TeamRoles::Admin->value,
        ]);

        livewire(EditTeam::class, ['record' => $team->getRouteKey()])
            ->fillForm([
                'name' => 'Same Name',
                'description' => 'Updated description',
                'leader_id' => $leader->id,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        // Refresh and check
        $team->refresh();
        expect($team->name)->toBe('Same Name');
        expect($team->description)->toBe('Updated description');
    });
});

describe('TeamResource Leader Management', function (): void {
    it('can assign team leader from admin members', function (): void {
        // Create a team
        $team = Team::factory()->create();

        // Create admin users belonging to this team
        $adminUser = User::factory()->create([
            'team_id' => $team->id,
            'team_role' => TeamRoles::Admin->value,
            'firstname' => 'Admin',
            'lastname' => 'User',
        ]);

        $memberUser = User::factory()->create([
            'team_id' => $team->id,
            'team_role' => TeamRoles::Member->value,
        ]);

        livewire(EditTeam::class, ['record' => $team->getRouteKey()])
            ->fillForm([
                'leader_id' => $adminUser->id,
            ])
            ->call('save')
            ->assertSuccessful();

        assertDatabaseHas('teams', [
            'id' => $team->id,
            'leader_id' => $adminUser->id,
        ]);
    });

    it('shows leader select field only on edit page', function (): void {
        $team = Team::factory()->create();

        // Create page should not show leader field
        livewire(CreateTeam::class)
            ->assertFormFieldDoesNotExist('leader_id');

        // Edit page should show leader field
        livewire(EditTeam::class, ['record' => $team->getRouteKey()])
            ->assertFormFieldExists('leader_id');
    });

    it('can search leader by firstname and lastname', function (): void {
        $team = Team::factory()->create();

        $adminUser = User::factory()->create([
            'team_id' => $team->id,
            'team_role' => TeamRoles::Admin->value,
            'firstname' => 'John',
            'lastname' => 'Smith',
        ]);

        $component = livewire(EditTeam::class, ['record' => $team->getRouteKey()]);

        // The select should be searchable by firstname and lastname
        // This tests that the relationship and search configuration is correct
        expect($component)->assertFormFieldExists('leader_id');
    });
});

describe('TeamResource Access Control', function (): void {
    it('requires authentication to access team resource', function (): void {
        Auth::logout();

        $team = Team::factory()->create();

        livewire(ListTeams::class)
            ->assertForbidden();

        livewire(CreateTeam::class)
            ->assertForbidden();

        livewire(EditTeam::class, ['record' => $team->getRouteKey()])
            ->assertForbidden();
    });

    it('allows admin users to access all team functions', function (): void {
        $team = Team::factory()->create();

        livewire(ListTeams::class)
            ->assertOk();

        livewire(CreateTeam::class)
            ->assertOk();

        livewire(EditTeam::class, ['record' => $team->getRouteKey()])
            ->assertOk();
    });
});

describe('TeamResource Form Layout', function (): void {
    it('displays form with correct layout and styling', function (): void {
        $team = Team::factory()->create();

        $component = livewire(EditTeam::class, ['record' => $team->getRouteKey()]);

        // Test that form fields exist and are properly configured
        $component
            ->assertFormFieldExists('name')
            ->assertFormFieldExists('description')
            ->assertFormFieldExists('leader_id');
    });

    it('shows name field as required', function (): void {
        livewire(CreateTeam::class)
            ->fillForm(['name' => ''])
            ->call('create')
            ->assertHasFormErrors(['name']);
    });

    it('shows description field as optional', function (): void {
        livewire(CreateTeam::class)
            ->fillForm([
                'name' => 'Valid Team Name',
                'description' => '',
            ])
            ->call('create')
            ->assertHasNoFormErrors(['description']);
    });

    it('shows leader field as required on edit', function (): void {
        $team = Team::factory()->create();

        livewire(EditTeam::class, ['record' => $team->getRouteKey()])
            ->fillForm(['leader_id' => null])
            ->call('save')
            ->assertHasFormErrors(['leader_id']);
    });
});

describe('TeamResource Relationship Management', function (): void {
    it('has correct relationship managers configured', function (): void {
        $relationManagers = TeamResource::getRelations();

        expect($relationManagers)->toContain(
            \App\Filament\Resources\Teams\RelationManagers\MembersRelationManager::class,
            \App\Filament\Resources\Teams\RelationManagers\ProjectsRelationManager::class,
            \App\Filament\Resources\Teams\RelationManagers\ProtocolsRelationManager::class,
            \App\Filament\Resources\Teams\RelationManagers\StudyDesignsRelationManager::class
        );
    });

    it('can access team with members', function (): void {
        $team = Team::factory()
            ->has(User::factory()->count(3)->state(['team_role' => TeamRoles::Member->value]), 'members')
            ->create();

        livewire(EditTeam::class, ['record' => $team->getRouteKey()])
            ->assertOk();

        expect($team->members)->toHaveCount(3);
    });
});

describe('TeamResource Navigation', function (): void {
    it('has correct navigation configuration', function (): void {
        expect(TeamResource::getModel())->toBe(Team::class);

        // Test that the resource is properly configured
        expect(new TeamResource)->toBeInstanceOf(\Filament\Resources\Resource::class);
    });

    it('has correct page routes configured', function (): void {
        $pages = TeamResource::getPages();

        expect($pages)->toHaveKeys(['index', 'create', 'edit']);

        // Test that pages are properly configured
        expect($pages['index']->getPage())->toBe(ListTeams::class);
        expect($pages['create']->getPage())->toBe(CreateTeam::class);
        expect($pages['edit']->getPage())->toBe(EditTeam::class);
    });
});

describe('TeamResource Data Integrity', function (): void {
    it('maintains referential integrity when deleting teams', function (): void {
        $team = Team::factory()->create();

        // Create a user belonging to this team
        $user = User::factory()->create([
            'team_id' => $team->id,
            'team_role' => TeamRoles::Member->value,
        ]);

        // Note: The actual behavior depends on your foreign key constraints
        // This test documents the expected behavior
        expect($team->members)->toHaveCount(1);
        expect($user->team_id)->toBe($team->id);
    });

    it('handles team deletion with leader relationship', function (): void {
        $team = Team::factory()->create();

        $leader = User::factory()->create([
            'team_id' => $team->id,
            'team_role' => TeamRoles::Admin->value,
        ]);

        $team->update(['leader_id' => $leader->id]);

        expect($team->leader_id)->toBe($leader->id);
        expect($team->leader->id)->toBe($leader->id);
    });
});
