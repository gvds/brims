<?php

use App\Enums\StudyRoles;
use App\Enums\TeamRoles;
use App\Filament\App\Resources\Studies\Pages\CreateStudy;
use App\Filament\App\Resources\Studies\Pages\EditStudy;
use App\Filament\App\Resources\Studies\Pages\ViewStudy;
use App\Filament\App\Resources\Studies\RelationManagers\MembersRelationManager;
use App\Models\Arm;
use App\Models\Event;
use App\Models\Project;
use App\Models\Site;
use App\Models\Specimen;
use App\Models\Specimentype;
use App\Models\Study;
use App\Models\Subject;
use App\Models\SubjectEvent;
use App\Models\Team;
use App\Models\User;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\EditAction;
use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    actingAs($this->adminuser);
    Filament::setCurrentPanel('app');
    Filament::bootCurrentPanel();
});

function createStudyLedBy(User $leader): Study
{
    $study = Study::factory()->create(['team_id' => $leader->team_id]);
    $study->members()->attach($leader, ['role' => StudyRoles::Lead->value]);

    return $study;
}

function createSpecimenInProject(Project $project, User $user): Specimen
{
    $site = Site::factory()->create(['project_id' => $project->id]);
    $arm = Arm::factory()->create(['project_id' => $project->id, 'arm_num' => 1]);
    $event = Event::factory()->create(['arm_id' => $arm->id]);
    $subject = Subject::factory()->create([
        'project_id' => $project->id,
        'site_id' => $site->id,
        'subjectID' => 'SUBJ-'.$project->id,
        'user_id' => $user->id,
    ]);
    $subjectEvent = SubjectEvent::create([
        'subject_id' => $subject->id,
        'event_id' => $event->id,
        'iteration' => 1,
    ]);

    return Specimen::factory()->create([
        'subject_event_id' => $subjectEvent->id,
        'specimenType_id' => Specimentype::factory()->create(['project_id' => $project->id])->id,
        'site_id' => $site->id,
        'origin_site_id' => $site->id,
        'project_id' => $project->id,
        'aliquot' => 1,
        'loggedBy_id' => $user->id,
        'loggedAt' => now(),
    ]);
}

it('creates a study owned by the team of the creating user and linked to the chosen projects', function (): void {
    $project = Project::factory()->create(['team_id' => $this->team->id, 'leader_id' => $this->adminuser->id]);

    livewire(CreateStudy::class)
        ->fillForm([
            'title' => 'Cohort Study',
            'identifier' => 'COHORT_001',
            'project' => [$project->id],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $study = Study::where('identifier', 'COHORT_001')->firstOrFail();

    expect($study->team_id)->toBe($this->team->id)
        ->and($study->project->modelKeys())->toBe([$project->id]);

    assertDatabaseHas('study_member', ['study_id' => $study->id, 'user_id' => $this->adminuser->id, 'role' => 'lead']);
});

it('ignores a team_id submitted by the creating user', function (): void {
    $otherTeam = Team::factory()->create();

    livewire(CreateStudy::class)
        ->fillForm(['title' => 'Cohort Study', 'identifier' => 'COHORT_001'])
        ->set('data.team_id', $otherTeam->id)
        ->call('create');

    assertDatabaseHas('studies', ['identifier' => 'COHORT_001', 'team_id' => $this->team->id]);
});

it('adds projects to an existing study', function (): void {
    $projects = Project::factory()->count(2)->create(['team_id' => $this->team->id, 'leader_id' => $this->adminuser->id]);
    $study = createStudyLedBy($this->adminuser);

    livewire(EditStudy::class, ['record' => $study->getKey()])
        ->fillForm(['project' => $projects->modelKeys()])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($study->project()->pluck('projects.id')->all())->toEqualCanonicalizing($projects->modelKeys());
});

it('removes a project whose specimens are not attached to the study', function (): void {
    $project = Project::factory()->create(['team_id' => $this->team->id, 'leader_id' => $this->adminuser->id]);
    $study = createStudyLedBy($this->adminuser);
    $study->project()->attach($project);
    createSpecimenInProject($project, $this->adminuser);

    livewire(EditStudy::class, ['record' => $study->getKey()])
        ->fillForm(['project' => []])
        ->call('save')
        ->assertHasNoFormErrors();

    assertDatabaseMissing('project_study', ['study_id' => $study->id, 'project_id' => $project->id]);
});

it('rejects removing a project while its specimens are attached to the study', function (): void {
    $project = Project::factory()->create(['team_id' => $this->team->id, 'leader_id' => $this->adminuser->id]);
    $study = createStudyLedBy($this->adminuser);
    $study->project()->attach($project);
    $study->specimens()->attach(createSpecimenInProject($project, $this->adminuser));

    livewire(EditStudy::class, ['record' => $study->getKey()])
        ->fillForm(['project' => []])
        ->call('save')
        ->assertHasFormErrors(['project']);

    assertDatabaseHas('project_study', ['study_id' => $study->id, 'project_id' => $project->id]);
});

it('keeps a project with attached specimens while another project is removed', function (): void {
    $keptProject = Project::factory()->create(['team_id' => $this->team->id, 'leader_id' => $this->adminuser->id]);
    $removedProject = Project::factory()->create(['team_id' => $this->team->id, 'leader_id' => $this->adminuser->id]);
    $study = createStudyLedBy($this->adminuser);
    $study->project()->attach([$keptProject->id, $removedProject->id]);
    $study->specimens()->attach(createSpecimenInProject($keptProject, $this->adminuser));

    livewire(EditStudy::class, ['record' => $study->getKey()])
        ->fillForm(['project' => [$keptProject->id]])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($study->project()->pluck('projects.id')->all())->toBe([$keptProject->id]);
});

it('attaches a member to a study with a role', function (): void {
    $study = createStudyLedBy($this->adminuser);
    $member = User::factory()->create(['team_id' => $this->team->id, 'team_role' => TeamRoles::Admin->value]);

    livewire(MembersRelationManager::class, ['ownerRecord' => $study, 'pageClass' => ViewStudy::class])
        ->callAction(TestAction::make(AttachAction::class)->table(), [
            'recordId' => $member->id,
            'role' => StudyRoles::Member->value,
        ])
        ->assertHasNoFormErrors();

    assertDatabaseHas('study_member', ['study_id' => $study->id, 'user_id' => $member->id, 'role' => 'member']);
});

it('edits the role of a study member without changing the user', function (): void {
    $study = createStudyLedBy($this->adminuser);
    $member = User::factory()->create(['team_id' => $this->team->id, 'firstname' => 'Unchanged']);
    $study->members()->attach($member, ['role' => StudyRoles::Member->value]);

    livewire(MembersRelationManager::class, ['ownerRecord' => $study, 'pageClass' => ViewStudy::class])
        ->mountAction(TestAction::make(EditAction::class)->table($member))
        ->assertSchemaStateSet(['role' => StudyRoles::Member])
        ->setActionData(['role' => StudyRoles::Lead->value])
        ->callMountedAction()
        ->assertHasNoFormErrors();

    assertDatabaseHas('study_member', ['study_id' => $study->id, 'user_id' => $member->id, 'role' => 'lead']);
    assertDatabaseHas('users', ['id' => $member->id, 'firstname' => 'Unchanged']);
});

it('rejects changing the role of the only lead of a study', function (): void {
    $lead = User::factory()->create(['team_id' => $this->team->id, 'team_role' => TeamRoles::Admin->value]);
    $study = createStudyLedBy($lead);
    actingAs($lead);

    livewire(MembersRelationManager::class, ['ownerRecord' => $study, 'pageClass' => ViewStudy::class])
        ->mountAction(TestAction::make(EditAction::class)->table($lead))
        ->setActionData(['role' => StudyRoles::Member->value])
        ->callMountedAction()
        ->assertHasFormErrors(['role']);

    assertDatabaseHas('study_member', ['study_id' => $study->id, 'user_id' => $lead->id, 'role' => 'lead']);
});

it('changes the role of a lead when the study has another lead', function (): void {
    $study = createStudyLedBy($this->adminuser);
    [$lead, $otherLead] = User::factory()->count(2)->create(['team_id' => $this->team->id]);
    $study->members()->attach([$lead->id, $otherLead->id], ['role' => StudyRoles::Lead->value]);

    livewire(MembersRelationManager::class, ['ownerRecord' => $study, 'pageClass' => ViewStudy::class])
        ->mountAction(TestAction::make(EditAction::class)->table($lead))
        ->setActionData(['role' => StudyRoles::Member->value])
        ->callMountedAction()
        ->assertHasNoFormErrors();

    assertDatabaseHas('study_member', ['study_id' => $study->id, 'user_id' => $lead->id, 'role' => 'member']);
});

it('does not allow detaching the only lead of a study', function (): void {
    $lead = User::factory()->create(['team_id' => $this->team->id, 'team_role' => TeamRoles::Admin->value]);
    $member = User::factory()->create(['team_id' => $this->team->id]);
    $study = createStudyLedBy($lead);
    $study->members()->attach($member, ['role' => StudyRoles::Member->value]);
    actingAs($lead);

    livewire(MembersRelationManager::class, ['ownerRecord' => $study, 'pageClass' => ViewStudy::class])
        ->assertActionDisabled(TestAction::make(DetachAction::class)->table($lead))
        ->assertActionEnabled(TestAction::make(DetachAction::class)->table($member));
});

it('searches users by name when choosing a member to attach', function (): void {
    $study = createStudyLedBy($this->adminuser);
    $match = User::factory()->create(['team_id' => $this->team->id, 'team_role' => TeamRoles::Admin->value, 'firstname' => 'Zelda', 'lastname' => 'Marsh']);
    User::factory()->create(['team_id' => $this->team->id, 'team_role' => TeamRoles::Admin->value, 'firstname' => 'Other', 'lastname' => 'Person']);

    livewire(MembersRelationManager::class, ['ownerRecord' => $study, 'pageClass' => ViewStudy::class])
        ->mountAction(TestAction::make(AttachAction::class)->table())
        ->assertSchemaComponentExists('recordId', 'mountedActionSchema0', function (Select $select) use ($match): bool {
            return $select->getSearchResults('Zelda') === [$match->id => 'Zelda Marsh'];
        });
});

it('only offers team admins and members of linked projects as attachable members', function (): void {
    $project = Project::factory()->create(['team_id' => $this->team->id, 'leader_id' => $this->adminuser->id]);
    $study = createStudyLedBy($this->adminuser);
    $study->project()->attach($project);
    $teamAdmin = User::factory()->create(['team_id' => $this->team->id, 'team_role' => TeamRoles::Admin->value, 'firstname' => 'Zqa']);
    $projectMember = User::factory()->create(['team_id' => $this->team->id, 'team_role' => TeamRoles::Member->value, 'firstname' => 'Zqb']);
    $project->members()->attach($projectMember->id, ['role_id' => 'Member']);
    User::factory()->create(['team_id' => $this->team->id, 'team_role' => TeamRoles::Member->value, 'firstname' => 'Zqc']);
    User::factory()->create(['team_id' => Team::factory()->create()->id, 'team_role' => TeamRoles::Admin->value, 'firstname' => 'Zqd']);

    livewire(MembersRelationManager::class, ['ownerRecord' => $study, 'pageClass' => ViewStudy::class])
        ->mountAction(TestAction::make(AttachAction::class)->table())
        ->assertSchemaComponentExists('recordId', 'mountedActionSchema0', function (Select $select) use ($teamAdmin, $projectMember): bool {
            return array_keys($select->getSearchResults('Zq')) === [$teamAdmin->id, $projectMember->id];
        });
});

it('rejects removing a project that a study member depends on for eligibility', function (): void {
    $project = Project::factory()->create(['team_id' => $this->team->id, 'leader_id' => $this->adminuser->id]);
    $study = createStudyLedBy($this->adminuser);
    $study->project()->attach($project);
    $projectMember = User::factory()->create(['team_id' => $this->team->id, 'team_role' => TeamRoles::Member->value]);
    $project->members()->attach($projectMember->id, ['role_id' => 'Member']);
    $study->members()->attach($projectMember, ['role' => StudyRoles::Lead->value]);

    livewire(EditStudy::class, ['record' => $study->getKey()])
        ->fillForm(['project' => []])
        ->call('save')
        ->assertHasFormErrors(['project']);

    assertDatabaseHas('project_study', ['study_id' => $study->id, 'project_id' => $project->id]);
});

it('removes a project when its members remain eligible as team admins', function (): void {
    $project = Project::factory()->create(['team_id' => $this->team->id, 'leader_id' => $this->adminuser->id]);
    $study = createStudyLedBy($this->adminuser);
    $study->project()->attach($project);
    $teamAdmin = User::factory()->create(['team_id' => $this->team->id, 'team_role' => TeamRoles::Admin->value]);
    $project->members()->attach($teamAdmin->id, ['role_id' => 'Member']);
    $study->members()->attach($teamAdmin, ['role' => StudyRoles::Lead->value]);

    livewire(EditStudy::class, ['record' => $study->getKey()])
        ->fillForm(['project' => []])
        ->call('save')
        ->assertHasNoFormErrors();

    assertDatabaseMissing('project_study', ['study_id' => $study->id, 'project_id' => $project->id]);
});

it('rejects creating a study when the creator is neither a team admin nor a member of the chosen projects', function (): void {
    $this->adminuser->update(['team_role' => TeamRoles::Member->value]);

    livewire(CreateStudy::class)
        ->fillForm(['title' => 'Cohort Study', 'identifier' => 'COHORT_001'])
        ->call('create')
        ->assertHasFormErrors(['project']);

    assertDatabaseMissing('studies', ['identifier' => 'COHORT_001']);
});

it('shows the study role name of each member in the members table', function (): void {
    $study = createStudyLedBy($this->adminuser);
    $lead = User::factory()->create(['team_id' => $this->team->id]);
    $study->members()->attach($lead, ['role' => StudyRoles::Lead->value]);

    livewire(MembersRelationManager::class, ['ownerRecord' => $study, 'pageClass' => ViewStudy::class])
        ->assertCanSeeTableRecords([$lead])
        ->assertTableColumnFormattedStateSet('pivot.role', 'Lead', record: $lead);
});

it('forbids editing a study to users who are not its lead', function (): void {
    $study = createStudyLedBy($this->adminuser);
    $nonLead = User::factory()->create(['team_id' => $this->team->id, 'team_role' => TeamRoles::Admin->value]);
    $study->members()->attach($nonLead, ['role' => StudyRoles::Member->value]);
    actingAs($nonLead);

    livewire(EditStudy::class, ['record' => $study->getKey()])
        ->assertForbidden();
});

it('hides the member actions from users who are not a lead of the study', function (): void {
    $study = createStudyLedBy($this->adminuser);
    $nonLead = User::factory()->create(['team_id' => $this->team->id, 'team_role' => TeamRoles::Admin->value]);
    $study->members()->attach($nonLead, ['role' => StudyRoles::Member->value]);
    actingAs($nonLead);

    livewire(MembersRelationManager::class, ['ownerRecord' => $study, 'pageClass' => ViewStudy::class])
        ->assertCanSeeTableRecords([$nonLead])
        ->assertActionHidden(TestAction::make(AttachAction::class)->table())
        ->assertActionHidden(TestAction::make(EditAction::class)->table($nonLead))
        ->assertActionHidden(TestAction::make(DetachAction::class)->table($nonLead));
});
