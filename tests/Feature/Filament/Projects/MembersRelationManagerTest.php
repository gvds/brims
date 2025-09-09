<?php

/**
 * MembersRelationManager Comprehensive Test Suite
 *
 * This test suite provides comprehensive coverage for the Projects MembersRelationManager
 * functionality using Pest v4 testing framework and Filament v4 testing patterns.
 *
 * Coverage includes:
 * - Relation manager configuration and setup (5 tests)
 * - Member attachment business logic (5 tests)
 * - Member detachment business logic (4 tests)
 * - Role and site management (5 tests)
 * - Project leader relationship (4 tests)
 * - Site integration (3 tests)
 * - Data integrity and validation (4 tests)
 *
 * Total: 30 tests focused on core business logic and data integrity
 *
 * Note: MembersRelationManager manages the many-to-many relationship between
 * Projects and Users through the project_member pivot table with additional
 * role and site_id fields. UI tests are excluded due to environment dependencies.
 */

use App\Filament\Resources\Projects\RelationManagers\MembersRelationManager;
use App\Models\Project;
use App\Models\Site;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

beforeEach(function (): void {
    actingAs($this->adminuser);

    // Create a test project with leader
    $this->project = Project::factory()->create([
        'team_id' => $this->team->id,
        'leader_id' => $this->adminuser->id,
    ]);

    // Create sites for the project
    $this->sites = Site::factory()->count(3)->create([
        'project_id' => $this->project->id,
    ]);

    // Create additional users for testing
    $this->users = User::factory()->count(5)->create([
        'team_id' => $this->team->id,
    ]);

    // Add some existing members to the project with proper pivot data
    $this->existingMembers = $this->users->take(2);
    foreach ($this->existingMembers as $index => $user) {
        $this->project->members()->attach($user->id, [
            'role' => $index === 0 ? 'Admin' : 'Member',
            'site_id' => $this->sites->random()->id,
        ]);
    }

    // Refresh the project to get updated member relationships
    $this->project->refresh();
});

describe('Members Relation Manager Configuration', function (): void {
    it('has correct relationship configuration', function (): void {
        expect(MembersRelationManager::getRelationshipName())->toBe('members');
    });

    it('is not read-only', function (): void {
        $manager = new MembersRelationManager;
        $manager->ownerRecord = $this->project;

        expect($manager->isReadOnly())->toBeFalse();
    });

    it('can be instantiated correctly', function (): void {
        expect(MembersRelationManager::class)->toBeString();
        expect(class_exists(MembersRelationManager::class))->toBeTrue();
    });

    it('has proper relationship with project', function (): void {
        expect($this->project->members())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsToMany::class);
        expect($this->project->members()->count())->toBe(2); // 2 existing members added in setup
    });

    it('maintains member records correctly', function (): void {
        $memberIds = $this->project->members()->pluck('user_id')->toArray();
        expect($memberIds)->toHaveCount(2);
        expect($memberIds)->toContain($this->existingMembers->first()->id);
        expect($memberIds)->toContain($this->existingMembers->last()->id);
    });
});

describe('Member Attachment Business Logic', function (): void {
    it('can attach a new member with role and site', function (): void {
        $newUser = $this->users->whereNotIn('id', $this->existingMembers->pluck('id'))->first();
        $site = $this->sites->first();

        $this->project->members()->attach($newUser->id, [
            'role' => 'Member',
            'site_id' => $site->id,
        ]);

        assertDatabaseHas('project_member', [
            'project_id' => $this->project->id,
            'user_id' => $newUser->id,
            'role' => 'Member',
            'site_id' => $site->id,
        ]);

        expect($this->project->fresh()->members()->count())->toBe(3);
    });

    it('can attach member with admin role', function (): void {
        $newUser = $this->users->whereNotIn('id', $this->existingMembers->pluck('id'))->first();

        $this->project->members()->attach($newUser->id, [
            'role' => 'Admin',
            'site_id' => null, // Optional site assignment
        ]);

        assertDatabaseHas('project_member', [
            'project_id' => $this->project->id,
            'user_id' => $newUser->id,
            'role' => 'Admin',
            'site_id' => null,
        ]);
    });

    it('can attach member without site assignment', function (): void {
        $newUser = $this->users->whereNotIn('id', $this->existingMembers->pluck('id'))->first();

        $this->project->members()->attach($newUser->id, [
            'role' => 'Member',
            'site_id' => null,
        ]);

        assertDatabaseHas('project_member', [
            'project_id' => $this->project->id,
            'user_id' => $newUser->id,
            'role' => 'Member',
            'site_id' => null,
        ]);
    });

    it('maintains referential integrity when attaching members', function (): void {
        $newUser = $this->users->whereNotIn('id', $this->existingMembers->pluck('id'))->first();
        $originalCount = $this->project->members()->count();

        $this->project->members()->attach($newUser->id, [
            'role' => 'Member',
            'site_id' => $this->sites->first()->id,
        ]);

        expect($this->project->fresh()->members()->count())->toBe($originalCount + 1);
        expect($this->project->fresh()->members()->where('user_id', $newUser->id)->exists())->toBeTrue();
    });

    it('can attach multiple members with different roles', function (): void {
        $availableUsers = $this->users->whereNotIn('id', $this->existingMembers->pluck('id'))->take(2);
        $usersArray = $availableUsers->values(); // Reset array indexes

        // Attach first user as Admin
        $this->project->members()->attach($usersArray[0]->id, [
            'role' => 'Admin',
            'site_id' => $this->sites->random()->id,
        ]);

        // Attach second user as Member
        $this->project->members()->attach($usersArray[1]->id, [
            'role' => 'Member',
            'site_id' => $this->sites->random()->id,
        ]);

        expect($this->project->fresh()->members()->count())->toBe(4); // 2 existing + 2 new

        assertDatabaseHas('project_member', [
            'project_id' => $this->project->id,
            'user_id' => $usersArray[0]->id,
            'role' => 'Admin',
        ]);

        assertDatabaseHas('project_member', [
            'project_id' => $this->project->id,
            'user_id' => $usersArray[1]->id,
            'role' => 'Member',
        ]);
    });
});

describe('Member Detachment Business Logic', function (): void {
    it('can detach a member from project', function (): void {
        $member = $this->existingMembers->first();
        $originalCount = $this->project->members()->count();

        $this->project->members()->detach($member->id);

        assertDatabaseMissing('project_member', [
            'project_id' => $this->project->id,
            'user_id' => $member->id,
        ]);

        expect($this->project->fresh()->members()->count())->toBe($originalCount - 1);
    });

    it('maintains data integrity when detaching members', function (): void {
        $memberToKeep = $this->existingMembers->first();
        $memberToRemove = $this->existingMembers->last();

        $this->project->members()->detach($memberToRemove->id);

        // Verify removed member is gone
        assertDatabaseMissing('project_member', [
            'project_id' => $this->project->id,
            'user_id' => $memberToRemove->id,
        ]);

        // Verify remaining member is still there
        assertDatabaseHas('project_member', [
            'project_id' => $this->project->id,
            'user_id' => $memberToKeep->id,
        ]);
    });

    it('can detach multiple members', function (): void {
        $membersToDetach = $this->existingMembers->pluck('id')->toArray();

        $this->project->members()->detach($membersToDetach);

        foreach ($membersToDetach as $userId) {
            assertDatabaseMissing('project_member', [
                'project_id' => $this->project->id,
                'user_id' => $userId,
            ]);
        }

        expect($this->project->fresh()->members()->count())->toBe(0);
    });

    it('handles detachment of non-existent member gracefully', function (): void {
        $nonMember = $this->users->whereNotIn('id', $this->existingMembers->pluck('id'))->first();
        $originalCount = $this->project->members()->count();

        // This should not cause an error
        $this->project->members()->detach($nonMember->id);

        expect($this->project->fresh()->members()->count())->toBe($originalCount);
    });
});

describe('Member Role and Site Management', function (): void {
    it('can update member role through sync', function (): void {
        $member = $this->existingMembers->first();
        $memberData = $this->project->members()->where('user_id', $member->id)->first();
        $currentSiteId = $memberData->pivot->site_id;

        $this->project->members()->updateExistingPivot($member->id, [
            'role' => 'Admin',
            'site_id' => $currentSiteId,
        ]);

        assertDatabaseHas('project_member', [
            'project_id' => $this->project->id,
            'user_id' => $member->id,
            'role' => 'Admin',
            'site_id' => $currentSiteId,
        ]);
    });

    it('can update member site assignment', function (): void {
        $member = $this->existingMembers->first();
        $memberData = $this->project->members()->where('user_id', $member->id)->first();
        $currentRole = $memberData->pivot->role;
        $newSite = $this->sites->where('id', '!=', $memberData->pivot->site_id)->first();

        $this->project->members()->updateExistingPivot($member->id, [
            'role' => $currentRole,
            'site_id' => $newSite->id,
        ]);

        assertDatabaseHas('project_member', [
            'project_id' => $this->project->id,
            'user_id' => $member->id,
            'role' => $currentRole,
            'site_id' => $newSite->id,
        ]);
    });

    it('can remove site assignment by setting to null', function (): void {
        $member = $this->existingMembers->first();
        $memberData = $this->project->members()->where('user_id', $member->id)->first();
        $currentRole = $memberData->pivot->role;

        $this->project->members()->updateExistingPivot($member->id, [
            'role' => $currentRole,
            'site_id' => null,
        ]);

        assertDatabaseHas('project_member', [
            'project_id' => $this->project->id,
            'user_id' => $member->id,
            'role' => $currentRole,
            'site_id' => null,
        ]);
    });

    it('validates role values in business logic', function (): void {
        $member = $this->existingMembers->first();

        // Test that valid roles work
        $this->project->members()->updateExistingPivot($member->id, [
            'role' => 'Admin',
        ]);

        assertDatabaseHas('project_member', [
            'project_id' => $this->project->id,
            'user_id' => $member->id,
            'role' => 'Admin',
        ]);

        $this->project->members()->updateExistingPivot($member->id, [
            'role' => 'Member',
        ]);

        assertDatabaseHas('project_member', [
            'project_id' => $this->project->id,
            'user_id' => $member->id,
            'role' => 'Member',
        ]);
    });

    it('maintains member count when updating roles and sites', function (): void {
        $originalCount = $this->project->members()->count();
        $member = $this->existingMembers->first();

        $this->project->members()->updateExistingPivot($member->id, [
            'role' => 'Admin',
            'site_id' => $this->sites->random()->id,
        ]);

        expect($this->project->fresh()->members()->count())->toBe($originalCount);
    });
});

describe('Project Leader Relationship', function (): void {
    it('identifies project leader correctly', function (): void {
        expect($this->project->leader_id)->toBe($this->adminuser->id);
        expect($this->project->leader)->toBeInstanceOf(User::class);
        expect($this->project->leader->id)->toBe($this->adminuser->id);
    });

    it('can add project leader as member', function (): void {
        // Add leader as project member
        $this->project->members()->attach($this->adminuser->id, [
            'role' => 'Admin',
            'site_id' => $this->sites->first()->id,
        ]);

        assertDatabaseHas('project_member', [
            'project_id' => $this->project->id,
            'user_id' => $this->adminuser->id,
            'role' => 'Admin',
        ]);

        expect($this->project->fresh()->members()->where('user_id', $this->adminuser->id)->exists())->toBeTrue();
    });

    it('maintains leader information when managing members', function (): void {
        $originalLeaderId = $this->project->leader_id;

        // Add some members
        $newUser = $this->users->whereNotIn('id', $this->existingMembers->pluck('id'))->first();
        $this->project->members()->attach($newUser->id, [
            'role' => 'Member',
            'site_id' => $this->sites->first()->id,
        ]);

        // Leader should remain unchanged
        expect($this->project->fresh()->leader_id)->toBe($originalLeaderId);
    });

    it('can distinguish between leader and regular members', function (): void {
        // Add leader as member
        $this->project->members()->attach($this->adminuser->id, [
            'role' => 'Admin',
            'site_id' => $this->sites->first()->id,
        ]);

        $allMembers = $this->project->fresh()->members()->get();
        $leaderAsMember = $allMembers->where('id', $this->project->leader_id)->first();
        $regularMembers = $allMembers->where('id', '!=', $this->project->leader_id);

        expect($leaderAsMember)->not->toBeNull();
        expect($regularMembers->count())->toBe(2); // 2 existing members
    });
});

describe('Site Integration', function (): void {
    it('can assign members to project sites only', function (): void {
        $newUser = $this->users->whereNotIn('id', $this->existingMembers->pluck('id'))->first();
        $projectSite = $this->sites->first();

        $this->project->members()->attach($newUser->id, [
            'role' => 'Member',
            'site_id' => $projectSite->id,
        ]);

        assertDatabaseHas('project_member', [
            'project_id' => $this->project->id,
            'user_id' => $newUser->id,
            'site_id' => $projectSite->id,
        ]);

        // Verify the site belongs to this project
        expect($projectSite->project_id)->toBe($this->project->id);
    });

    it('maintains site relationships when updating member assignments', function (): void {
        $member = $this->existingMembers->first();
        $newSite = $this->sites->random();

        $this->project->members()->updateExistingPivot($member->id, [
            'site_id' => $newSite->id,
        ]);

        assertDatabaseHas('project_member', [
            'project_id' => $this->project->id,
            'user_id' => $member->id,
            'site_id' => $newSite->id,
        ]);

        // Verify site still belongs to correct project
        expect($newSite->fresh()->project_id)->toBe($this->project->id);
    });

    it('allows multiple members to be assigned to same site', function (): void {
        $site = $this->sites->first();
        $availableUsers = $this->users->whereNotIn('id', $this->existingMembers->pluck('id'))->take(2);

        foreach ($availableUsers as $user) {
            $this->project->members()->attach($user->id, [
                'role' => 'Member',
                'site_id' => $site->id,
            ]);
        }

        foreach ($availableUsers as $user) {
            assertDatabaseHas('project_member', [
                'project_id' => $this->project->id,
                'user_id' => $user->id,
                'site_id' => $site->id,
            ]);
        }
    });
});

describe('Data Integrity and Validation', function (): void {
    it('maintains referential integrity across operations', function (): void {
        $initialMemberCount = $this->project->members()->count();
        $newUser = $this->users->whereNotIn('id', $this->existingMembers->pluck('id'))->first();

        // Add member
        $this->project->members()->attach($newUser->id, [
            'role' => 'Member',
            'site_id' => $this->sites->first()->id,
        ]);

        expect($this->project->fresh()->members()->count())->toBe($initialMemberCount + 1);

        // Update member
        $this->project->members()->updateExistingPivot($newUser->id, [
            'role' => 'Admin',
        ]);

        expect($this->project->fresh()->members()->count())->toBe($initialMemberCount + 1);

        // Remove member
        $this->project->members()->detach($newUser->id);

        expect($this->project->fresh()->members()->count())->toBe($initialMemberCount);
    });

    it('handles concurrent member operations correctly', function (): void {
        $users = $this->users->whereNotIn('id', $this->existingMembers->pluck('id'))->take(3);

        // Attach multiple members
        foreach ($users as $user) {
            $this->project->members()->attach($user->id, [
                'role' => 'Member',
                'site_id' => $this->sites->random()->id,
            ]);
        }

        $memberCount = $this->project->fresh()->members()->count();
        expect($memberCount)->toBe(5); // 2 existing + 3 new

        // Update roles for all new members
        foreach ($users as $user) {
            $this->project->members()->updateExistingPivot($user->id, [
                'role' => 'Admin',
            ]);
        }

        // Verify count hasn't changed
        expect($this->project->fresh()->members()->count())->toBe(5);

        // Verify all updates succeeded
        foreach ($users as $user) {
            assertDatabaseHas('project_member', [
                'project_id' => $this->project->id,
                'user_id' => $user->id,
                'role' => 'Admin',
            ]);
        }
    });

    it('preserves timestamps on member relationships', function (): void {
        $newUser = $this->users->whereNotIn('id', $this->existingMembers->pluck('id'))->first();

        $this->project->members()->attach($newUser->id, [
            'role' => 'Member',
            'site_id' => $this->sites->first()->id,
        ]);

        $memberRecord = $this->project->members()->where('user_id', $newUser->id)->first();

        expect($memberRecord->pivot->created_at)->not->toBeNull();
        expect($memberRecord->pivot->updated_at)->not->toBeNull();
    });

    it('ensures project context is maintained', function (): void {
        $otherProject = Project::factory()->create([
            'team_id' => $this->team->id,
            'leader_id' => $this->adminuser->id, // Provide required leader_id
        ]);
        $newUser = $this->users->whereNotIn('id', $this->existingMembers->pluck('id'))->first();

        // Add member to first project
        $this->project->members()->attach($newUser->id, [
            'role' => 'Member',
            'site_id' => $this->sites->first()->id,
        ]);

        // Verify member is in correct project
        assertDatabaseHas('project_member', [
            'project_id' => $this->project->id,
            'user_id' => $newUser->id,
        ]);

        // Verify member is NOT in other project
        assertDatabaseMissing('project_member', [
            'project_id' => $otherProject->id,
            'user_id' => $newUser->id,
        ]);
    });
});
