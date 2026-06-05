<?php

declare(strict_types=1);

use App\Enums\SubjectStatus;
use App\Enums\SystemRoles;
use App\Enums\TeamRoles;
use App\Models\Permission;
use App\Models\Project;
use App\Models\Site;
use App\Models\Subject;
use App\Models\Team;
use App\Models\User;
use App\Policies\SubjectPolicy;
use Spatie\Permission\PermissionRegistrar;

/**
 * Build a project with its owning team and a site, and store the project in session.
 * Also sets the Spatie permissions team ID so that permission assignments and
 * checks use the correct project scope.
 *
 * @return array{team: Team, project: Project, site: Site}
 */
function makeProjectInSession(): array
{
    $team = Team::factory()->create();
    $leader = User::factory()->create([
        'system_role' => SystemRoles::SuperAdmin,
        'team_id' => $team->id,
    ]);
    $project = Project::factory()
        ->for($team)
        ->for($leader, 'leader')
        ->create(['redcapProject_id' => null]);
    $site = Site::factory()->for($project)->create();
    session(['currentProject' => $project]);
    setPermissionsTeamId($project->id);

    return compact('team', 'project', 'site');
}

/**
 * Grant a Spatie permission to a user within the given project scope, then
 * flush the permission cache so the grant is visible immediately.
 */
function grantSubjectPermission(User $user, string $permissionName, Project $project): void
{
    setPermissionsTeamId($project->id);
    $permission = Permission::firstOrCreate(['name' => $permissionName]);
    $user->givePermissionTo($permission);
    app(PermissionRegistrar::class)->forgetCachedPermissions();
}

// ---------------------------------------------------------------------------
// viewAny
// ---------------------------------------------------------------------------

describe('viewAny', function (): void {
    it('allows a SysAdmin to viewAny subjects', function (): void {
        $sysAdmin = User::factory()->create(['system_role' => SystemRoles::SysAdmin]);

        expect($sysAdmin->can('viewAny', Subject::class))->toBeTrue();
    });

    it('allows a SuperAdmin to viewAny subjects via Gate::before bypass', function (): void {
        $superAdmin = User::factory()->create(['system_role' => SystemRoles::SuperAdmin]);

        expect($superAdmin->can('viewAny', Subject::class))->toBeTrue();
    });

    it('allows a team admin whose project belongs to their team to viewAny subjects', function (): void {
        ['team' => $team] = makeProjectInSession();

        $teamAdmin = User::factory()->create([
            'system_role' => SystemRoles::User,
            'team_id' => $team->id,
            'team_role' => TeamRoles::Admin->value,
        ]);

        expect($teamAdmin->can('viewAny', Subject::class))->toBeTrue();
    });

    it('allows a user with View:Subject permission to viewAny subjects', function (): void {
        ['project' => $project] = makeProjectInSession();
        $user = User::factory()->create(['system_role' => SystemRoles::User]);
        grantSubjectPermission($user, 'View:Subject', $project);

        expect($user->can('viewAny', Subject::class))->toBeTrue();
    });

    it('denies a regular user without permissions from viewing any subjects', function (): void {
        $user = User::factory()->create(['system_role' => SystemRoles::User]);

        expect($user->can('viewAny', Subject::class))->toBeFalse();
    });
});

// ---------------------------------------------------------------------------
// view — string argument (resource-level check, no record)
// ---------------------------------------------------------------------------

describe('view with a string argument', function (): void {
    it('allows a SysAdmin to view (string)', function (): void {
        $sysAdmin = User::factory()->create(['system_role' => SystemRoles::SysAdmin]);
        $policy = new SubjectPolicy;

        expect($policy->view($sysAdmin, 'some-identifier'))->toBeTrue();
    });

    it('allows a user with View:Subject permission to view (string)', function (): void {
        ['project' => $project] = makeProjectInSession();
        $user = User::factory()->create(['system_role' => SystemRoles::User]);
        grantSubjectPermission($user, 'View:Subject', $project);
        $policy = new SubjectPolicy;

        expect($policy->view($user, 'some-identifier'))->toBeTrue();
    });

    it('denies a user without permissions to view (string)', function (): void {
        $user = User::factory()->create(['system_role' => SystemRoles::User]);
        $policy = new SubjectPolicy;

        expect($policy->view($user, 'some-identifier'))->toBeFalse();
    });
});

// ---------------------------------------------------------------------------
// view — Subject model argument (record-level check)
// ---------------------------------------------------------------------------

describe('view with a Subject model', function (): void {
    it('allows a SysAdmin to view an enrolled subject', function (): void {
        ['project' => $project, 'site' => $site] = makeProjectInSession();
        $sysAdmin = User::factory()->create(['system_role' => SystemRoles::SysAdmin]);
        $subject = Subject::factory()->create([
            'subjectID' => 'SUBJ001',
            'project_id' => $project->id,
            'site_id' => $site->id,
            'user_id' => $sysAdmin->id,
            'status' => SubjectStatus::Enrolled,
        ]);

        expect($sysAdmin->can('view', $subject))->toBeTrue();
    });

    it('denies a SysAdmin from viewing a Generated subject', function (): void {
        ['project' => $project, 'site' => $site] = makeProjectInSession();
        $sysAdmin = User::factory()->create(['system_role' => SystemRoles::SysAdmin]);
        $subject = Subject::factory()->create([
            'subjectID' => 'SUBJ002',
            'project_id' => $project->id,
            'site_id' => $site->id,
            'user_id' => $sysAdmin->id,
            'status' => SubjectStatus::Generated,
        ]);

        expect($sysAdmin->can('view', $subject))->toBeFalse();
    });

    it('allows a SuperAdmin to view a Generated subject via Gate::before bypass', function (): void {
        ['project' => $project, 'site' => $site] = makeProjectInSession();
        $superAdmin = User::factory()->create(['system_role' => SystemRoles::SuperAdmin]);
        $subject = Subject::factory()->create([
            'subjectID' => 'SUBJ003',
            'project_id' => $project->id,
            'site_id' => $site->id,
            'user_id' => $superAdmin->id,
            'status' => SubjectStatus::Generated,
        ]);

        expect($superAdmin->can('view', $subject))->toBeTrue();
    });

    it('allows a user with View:Subject permission to view their own enrolled subject', function (): void {
        ['project' => $project, 'site' => $site] = makeProjectInSession();
        $user = User::factory()->create(['system_role' => SystemRoles::User]);
        grantSubjectPermission($user, 'View:Subject', $project);
        $subject = Subject::factory()->create([
            'subjectID' => 'SUBJ004',
            'project_id' => $project->id,
            'site_id' => $site->id,
            'user_id' => $user->id,
            'status' => SubjectStatus::Enrolled,
        ]);

        expect($user->can('view', $subject))->toBeTrue();
    });

    it('denies a user with View:Subject permission from viewing a Generated subject', function (): void {
        ['project' => $project, 'site' => $site] = makeProjectInSession();
        $user = User::factory()->create(['system_role' => SystemRoles::User]);
        grantSubjectPermission($user, 'View:Subject', $project);
        $subject = Subject::factory()->create([
            'subjectID' => 'SUBJ005',
            'project_id' => $project->id,
            'site_id' => $site->id,
            'user_id' => $user->id,
            'status' => SubjectStatus::Generated,
        ]);

        expect($user->can('view', $subject))->toBeFalse();
    });

    it("denies a user with View:Subject permission from viewing another user's subject", function (): void {
        ['project' => $project, 'site' => $site] = makeProjectInSession();
        $user = User::factory()->create(['system_role' => SystemRoles::User]);
        $otherUser = User::factory()->create(['system_role' => SystemRoles::User]);
        grantSubjectPermission($user, 'View:Subject', $project);
        $subject = Subject::factory()->create([
            'subjectID' => 'SUBJ006',
            'project_id' => $project->id,
            'site_id' => $site->id,
            'user_id' => $otherUser->id,
            'status' => SubjectStatus::Enrolled,
        ]);

        expect($user->can('view', $subject))->toBeFalse();
    });

    it('denies a user without View:Subject permission from viewing their own subject', function (): void {
        ['project' => $project, 'site' => $site] = makeProjectInSession();
        $user = User::factory()->create(['system_role' => SystemRoles::User]);
        $subject = Subject::factory()->create([
            'subjectID' => 'SUBJ007',
            'project_id' => $project->id,
            'site_id' => $site->id,
            'user_id' => $user->id,
            'status' => SubjectStatus::Enrolled,
        ]);

        expect($user->can('view', $subject))->toBeFalse();
    });

    it('allows a team admin to view an enrolled subject in their team project', function (): void {
        ['team' => $team, 'project' => $project, 'site' => $site] = makeProjectInSession();
        $teamAdmin = User::factory()->create([
            'system_role' => SystemRoles::User,
            'team_id' => $team->id,
            'team_role' => TeamRoles::Admin->value,
        ]);
        $owner = User::factory()->create(['system_role' => SystemRoles::User]);
        $subject = Subject::factory()->create([
            'subjectID' => 'SUBJ008',
            'project_id' => $project->id,
            'site_id' => $site->id,
            'user_id' => $owner->id,
            'status' => SubjectStatus::Enrolled,
        ]);

        expect($teamAdmin->can('view', $subject))->toBeTrue();
    });

    it('allows a user to view an enrolled subject belonging to their substitutee', function (): void {
        ['project' => $project, 'site' => $site] = makeProjectInSession();
        $supervisor = User::factory()->create(['system_role' => SystemRoles::User]);
        $substitutee = User::factory()->create(['system_role' => SystemRoles::User]);
        grantSubjectPermission($supervisor, 'View:Subject', $project);

        // The substitutee is a project member whose substitute is the supervisor.
        $project->members()->attach($substitutee->id, [
            'substitute_id' => $supervisor->id,
            'site_id' => $site->id,
            'role_id' => 'Member',
        ]);

        $subject = Subject::factory()->create([
            'subjectID' => 'SUBJ009',
            'project_id' => $project->id,
            'site_id' => $site->id,
            'user_id' => $substitutee->id,
            'status' => SubjectStatus::Enrolled,
        ]);

        expect($supervisor->can('view', $subject))->toBeTrue();
    });
});

// ---------------------------------------------------------------------------
// create
// ---------------------------------------------------------------------------

describe('create', function (): void {
    it('allows a SysAdmin to create subjects', function (): void {
        $sysAdmin = User::factory()->create(['system_role' => SystemRoles::SysAdmin]);

        expect($sysAdmin->can('create', Subject::class))->toBeTrue();
    });

    it('allows a user with Manage:Subject permission to create subjects', function (): void {
        ['project' => $project] = makeProjectInSession();
        $user = User::factory()->create(['system_role' => SystemRoles::User]);
        grantSubjectPermission($user, 'Manage:Subject', $project);

        expect($user->can('create', Subject::class))->toBeTrue();
    });

    it('denies a regular user without permissions from creating subjects', function (): void {
        $user = User::factory()->create(['system_role' => SystemRoles::User]);

        expect($user->can('create', Subject::class))->toBeFalse();
    });
});

// ---------------------------------------------------------------------------
// update — string argument
// ---------------------------------------------------------------------------

describe('update with a string argument', function (): void {
    it('allows a SysAdmin to update (string)', function (): void {
        $sysAdmin = User::factory()->create(['system_role' => SystemRoles::SysAdmin]);
        $policy = new SubjectPolicy;

        expect($policy->update($sysAdmin, 'some-identifier'))->toBeTrue();
    });

    it('allows a user with Manage:Subject permission to update (string)', function (): void {
        ['project' => $project] = makeProjectInSession();
        $user = User::factory()->create(['system_role' => SystemRoles::User]);
        grantSubjectPermission($user, 'Manage:Subject', $project);
        $policy = new SubjectPolicy;

        expect($policy->update($user, 'some-identifier'))->toBeTrue();
    });

    it('denies a user without permissions to update (string)', function (): void {
        $user = User::factory()->create(['system_role' => SystemRoles::User]);
        $policy = new SubjectPolicy;

        expect($policy->update($user, 'some-identifier'))->toBeFalse();
    });
});

// ---------------------------------------------------------------------------
// update — Subject model argument
// ---------------------------------------------------------------------------

describe('update with a Subject model', function (): void {
    it('allows a SysAdmin to update their own subject', function (): void {
        ['project' => $project, 'site' => $site] = makeProjectInSession();
        $sysAdmin = User::factory()->create(['system_role' => SystemRoles::SysAdmin]);
        $subject = Subject::factory()->create([
            'subjectID' => 'SUBJ010',
            'project_id' => $project->id,
            'site_id' => $site->id,
            'user_id' => $sysAdmin->id,
            'status' => SubjectStatus::Enrolled,
        ]);

        expect($sysAdmin->can('update', $subject))->toBeTrue();
    });

    it('allows a user with Manage:Subject permission to update their own subject', function (): void {
        ['project' => $project, 'site' => $site] = makeProjectInSession();
        $user = User::factory()->create(['system_role' => SystemRoles::User]);
        grantSubjectPermission($user, 'Manage:Subject', $project);
        $subject = Subject::factory()->create([
            'subjectID' => 'SUBJ011',
            'project_id' => $project->id,
            'site_id' => $site->id,
            'user_id' => $user->id,
            'status' => SubjectStatus::Enrolled,
        ]);

        expect($user->can('update', $subject))->toBeTrue();
    });

    it("denies a user with Manage:Subject permission from updating another user's subject", function (): void {
        ['project' => $project, 'site' => $site] = makeProjectInSession();
        $user = User::factory()->create(['system_role' => SystemRoles::User]);
        $otherUser = User::factory()->create(['system_role' => SystemRoles::User]);
        grantSubjectPermission($user, 'Manage:Subject', $project);
        $subject = Subject::factory()->create([
            'subjectID' => 'SUBJ012',
            'project_id' => $project->id,
            'site_id' => $site->id,
            'user_id' => $otherUser->id,
            'status' => SubjectStatus::Enrolled,
        ]);

        expect($user->can('update', $subject))->toBeFalse();
    });

    it('denies a user without permissions from updating a subject', function (): void {
        ['project' => $project, 'site' => $site] = makeProjectInSession();
        $user = User::factory()->create(['system_role' => SystemRoles::User]);
        $subject = Subject::factory()->create([
            'subjectID' => 'SUBJ013',
            'project_id' => $project->id,
            'site_id' => $site->id,
            'user_id' => $user->id,
            'status' => SubjectStatus::Enrolled,
        ]);

        expect($user->can('update', $subject))->toBeFalse();
    });
});

// ---------------------------------------------------------------------------
// delete — string argument
// ---------------------------------------------------------------------------

describe('delete with a string argument', function (): void {
    it('allows a SysAdmin to delete (string)', function (): void {
        $sysAdmin = User::factory()->create(['system_role' => SystemRoles::SysAdmin]);
        $policy = new SubjectPolicy;

        expect($policy->delete($sysAdmin, 'some-identifier'))->toBeTrue();
    });

    it('allows a user with Delete:Subject permission to delete (string)', function (): void {
        ['project' => $project] = makeProjectInSession();
        $user = User::factory()->create(['system_role' => SystemRoles::User]);
        grantSubjectPermission($user, 'Delete:Subject', $project);
        $policy = new SubjectPolicy;

        expect($policy->delete($user, 'some-identifier'))->toBeTrue();
    });

    it('denies a user without permissions to delete (string)', function (): void {
        $user = User::factory()->create(['system_role' => SystemRoles::User]);
        $policy = new SubjectPolicy;

        expect($policy->delete($user, 'some-identifier'))->toBeFalse();
    });
});

// ---------------------------------------------------------------------------
// delete — Subject model argument
// ---------------------------------------------------------------------------

describe('delete with a Subject model', function (): void {
    it('allows a SysAdmin to delete their own subject', function (): void {
        ['project' => $project, 'site' => $site] = makeProjectInSession();
        $sysAdmin = User::factory()->create(['system_role' => SystemRoles::SysAdmin]);
        $subject = Subject::factory()->create([
            'subjectID' => 'SUBJ014',
            'project_id' => $project->id,
            'site_id' => $site->id,
            'user_id' => $sysAdmin->id,
            'status' => SubjectStatus::Enrolled,
        ]);

        expect($sysAdmin->can('delete', $subject))->toBeTrue();
    });

    it('allows a user with Delete:Subject permission to delete their own subject', function (): void {
        ['project' => $project, 'site' => $site] = makeProjectInSession();
        $user = User::factory()->create(['system_role' => SystemRoles::User]);
        grantSubjectPermission($user, 'Delete:Subject', $project);
        $subject = Subject::factory()->create([
            'subjectID' => 'SUBJ015',
            'project_id' => $project->id,
            'site_id' => $site->id,
            'user_id' => $user->id,
            'status' => SubjectStatus::Enrolled,
        ]);

        expect($user->can('delete', $subject))->toBeTrue();
    });

    it("denies a user with Delete:Subject permission from deleting another user's subject", function (): void {
        ['project' => $project, 'site' => $site] = makeProjectInSession();
        $user = User::factory()->create(['system_role' => SystemRoles::User]);
        $otherUser = User::factory()->create(['system_role' => SystemRoles::User]);
        grantSubjectPermission($user, 'Delete:Subject', $project);
        $subject = Subject::factory()->create([
            'subjectID' => 'SUBJ016',
            'project_id' => $project->id,
            'site_id' => $site->id,
            'user_id' => $otherUser->id,
            'status' => SubjectStatus::Enrolled,
        ]);

        expect($user->can('delete', $subject))->toBeFalse();
    });

    it('denies a user without permissions from deleting a subject', function (): void {
        ['project' => $project, 'site' => $site] = makeProjectInSession();
        $user = User::factory()->create(['system_role' => SystemRoles::User]);
        $subject = Subject::factory()->create([
            'subjectID' => 'SUBJ017',
            'project_id' => $project->id,
            'site_id' => $site->id,
            'user_id' => $user->id,
            'status' => SubjectStatus::Enrolled,
        ]);

        expect($user->can('delete', $subject))->toBeFalse();
    });
});

// ---------------------------------------------------------------------------
// deleteAny
// ---------------------------------------------------------------------------

describe('deleteAny', function (): void {
    it('allows a SysAdmin to deleteAny subjects', function (): void {
        $sysAdmin = User::factory()->create(['system_role' => SystemRoles::SysAdmin]);

        expect($sysAdmin->can('deleteAny', Subject::class))->toBeTrue();
    });

    it('allows a user with Delete:Subject permission to deleteAny subjects', function (): void {
        ['project' => $project] = makeProjectInSession();
        $user = User::factory()->create(['system_role' => SystemRoles::User]);
        grantSubjectPermission($user, 'Delete:Subject', $project);

        expect($user->can('deleteAny', Subject::class))->toBeTrue();
    });

    it('denies a regular user without permissions from deleting any subjects', function (): void {
        $user = User::factory()->create(['system_role' => SystemRoles::User]);

        expect($user->can('deleteAny', Subject::class))->toBeFalse();
    });
});
