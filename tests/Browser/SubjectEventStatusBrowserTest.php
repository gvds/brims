<?php

use App\Enums\EventStatus;
use App\Models\Arm;
use App\Models\Event;
use App\Models\Project;
use App\Models\Site;
use App\Models\Subject;
use App\Models\SubjectEvent;
use Illuminate\Support\Facades\Session;

use function Pest\Laravel\actingAs;

beforeEach(function (): void {
    $this->user = $this->adminuser;
    actingAs($this->user);

    $this->project = Project::factory()
        ->for($this->team)
        ->for($this->user, 'leader')
        ->has(Site::factory()->count(1))
        ->create();

    // project factory already sets the leader relationship; explicit attach not required for this test
    // (omitted to keep the test compatible with trimmed test schemas)

    // Create subject, arm, event and subjectEvent
    $this->subject = Subject::factory()
        ->for($this->project)
        ->for($this->project->sites->first(), 'site')
        ->for($this->user)
        ->create([
            'subjectID' => 'BRWS-001',
        ]);

    $this->arm = Arm::factory()
        ->for($this->project)
        ->create([ 'arm_num' => 1 ]);

    $this->event = Event::factory()
        ->for($this->arm)
        ->create();

    $this->subjectEvent = SubjectEvent::create([
        'subject_id' => $this->subject->id,
        'event_id' => $this->event->id,
        'status' => EventStatus::Scheduled->value,
    ]);

    Session::put('currentProject', $this->project);

    // Ensure tenant middleware permits the request in trimmed test DBs by creating
    // the expected pivot row directly (avoid using attach() because some test DB
    // schemas require different pivot fields).
    \Illuminate\Support\Facades\DB::table('project_member')->insert([
        'user_id' => $this->user->id,
        'project_id' => $this->project->id,
        'site_id' => $this->project->sites->first()->id,
        'role_id' => 'Admin',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
});

it('shows status as read-only for users without the update.event permission (browser)', function (): void {
    // allow viewing the record without depending on project pivot data
    \Illuminate\Support\Facades\Gate::before(fn ($user, $ability) => $ability === 'view' ? true : null);

    $page = visit(route('filament.project.resources.subjects.view', ['tenant' => $this->project->id, 'record' => $this->subject->id]))
        ->assertSee('Status')
        ->assertSee(EventStatus::Scheduled->name);

    // Ensure there is no editable control in the status cell. Check multiple selectors to be resilient
    $page->assertNotPresent('table tbody tr td select')
        ->assertNotPresent('[role="combobox"]')
        ->assertNotPresent('button[aria-haspopup="listbox"]');
});

it('shows status as editable for users with the update.event permission (browser)', function (): void {
    // allow viewing the record and stub update.event for editability
    \Illuminate\Support\Facades\Gate::before(fn ($user, $ability) => $ability === 'view' ? true : null);
    \Illuminate\Support\Facades\Gate::define('update.event', fn ($actor) => $actor->id === $this->user->id);

    $page = visit(route('filament.project.resources.subjects.view', ['tenant' => $this->project->id, 'record' => $this->subject->id]))
        ->assertSee('Status');

    // Filament may render the editable SelectColumn as a native select, combobox role, or a button that opens a listbox.
    // Assert that at least one of those is present (this proves the column is editable).
    $page->assertPresent('table tbody tr td select, [role="combobox"], button[aria-haspopup="listbox"]');
});

it('shows status as editable when user has a project-scoped role with update.event (browser)', function (): void {
    // create a project-scoped permission and role and assign to the user using DB inserts
    $permissionId = \Spatie\Permission\Models\Permission::create(['name' => 'update.event', 'project_id' => $this->project->id])->id;
    $viewPermissionId = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'View:Subject', 'project_id' => $this->project->id])->id;

    $role = \App\Models\Role::create(['name' => 'Project Updater', 'project_id' => $this->project->id]);

    // attach permissions to role (spatie pivot)
    \Illuminate\Support\Facades\DB::table('role_has_permissions')->insert([
        ['permission_id' => $permissionId, 'role_id' => $role->id],
        ['permission_id' => $viewPermissionId, 'role_id' => $role->id],
    ]);

    // assign role to user in model_has_roles (team-aware) and ensure project_member pivot exists
    \Illuminate\Support\Facades\DB::table('model_has_roles')->updateOrInsert([
        'role_id' => $role->id,
        'model_type' => \App\Models\User::class,
        'model_id' => $this->user->id,
        'project_id' => $this->project->id,
    ], []);

    \Illuminate\Support\Facades\DB::table('project_member')->updateOrInsert([
        'user_id' => $this->user->id,
        'project_id' => $this->project->id,
    ], [
        'site_id' => $this->project->sites->first()->id,
        'role_id' => $role->id,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // ensure current project is set for tenant + team resolver
    \Illuminate\Support\Facades\Session::put('currentProject', $this->project);

    // refresh the user instance so newly-attached role/permission relations are reloaded
    $this->user = $this->user->fresh()->unsetRelation('roles')->unsetRelation('permissions');

    actingAs($this->user);

    // visit the subject view — permission should be evaluated in project context
    $page = visit(route('filament.project.resources.subjects.view', ['tenant' => $this->project->id, 'record' => $this->subject->id]))
        ->assertSee('Status')
        ->assertPresent('table tbody tr td select, [role="combobox"], button[aria-haspopup="listbox"]');
});
