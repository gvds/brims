<?php

use App\Models\Project;
use App\Filament\Resources\Projects\Pages\CreateProject;
use App\Filament\Resources\Projects\Pages\EditProject;
use App\Filament\Resources\Projects\Pages\ListProjects;
use App\Filament\Resources\Projects\Pages\ViewProject;
use Filament\Actions\DeleteAction;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Livewire\livewire;

it('shows empty state when no projects exist', function (): void {
    actingAs($this->adminuser);
    Livewire::test(ListProjects::class)
        ->assertSee('No projects');
});

it('can list projects in the table', function (): void {
    actingAs($this->adminuser);
    $projects = Project::factory()->count(3)->create([
        'team_id' => $this->team->id,
        'leader_id' => auth()->id()
    ]);

    livewire(ListProjects::class)
        ->assertCanSeeTableRecords($projects);
});

it('can search projects by name', function (): void {
    actingAs($this->adminuser);
    $projects = Project::factory()->count(3)->create([
        'team_id' => $this->team->id,
        'leader_id' => auth()->id()
    ]);

    livewire(ListProjects::class)
        ->searchTable($projects->first()->name)
        ->assertCanSeeTableRecords($projects->take(1))
        ->assertCanNotSeeTableRecords($projects->where('name', '!=', $projects->first()->name));
});

it('can create a project', function (): void {
    actingAs($this->adminuser);
    $data = Project::factory()->make([
        'team_id' => $this->team->id,
        'leader_id' => auth()->id()
    ])->toArray();

    livewire(CreateProject::class)
        ->fillForm($data)
        ->call('create')
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseHas(Project::class, [
        'title' => $data['title'],
    ]);
});

it('cannot create a project with missing required fields', function (): void {
    actingAs($this->adminuser);
    livewire(CreateProject::class)
        ->fillForm(['title' => ''])
        ->call('create')
        ->assertHasFormErrors(['title' => 'required']);
});

it('can view a project', function (): void {
    actingAs($this->adminuser);
    $project = Project::factory()->create([
        'team_id' => $this->team->id,
        'leader_id' => auth()->id()
    ]);

    livewire(ViewProject::class, [
        'record' => $project->getKey(),
    ])->assertOk()
        ->assertSee($project->title);
});

it('can edit a project', function (): void {
    actingAs($this->adminuser);
    $project = Project::factory()->create([
        'team_id' => $this->team->id,
        'leader_id' => auth()->id()
    ]);
    $newTitle = 'Updated Project Name';

    livewire(EditProject::class, [
        'record' => $project->getKey(),
    ])->fillForm([
        'title' => $newTitle,
    ])->call('save')
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseHas(Project::class, [
        'id' => $project->id,
        'title' => $newTitle,
    ]);
});

it('can delete a project', function (): void {
    actingAs($this->adminuser);
    $project = Project::factory()->create([
        'team_id' => $this->team->id,
        'leader_id' => auth()->id()
    ]);

    livewire(EditProject::class, [
        'record' => $project->getKey(),
    ])->callAction(DeleteAction::class)
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseMissing(Project::class, [
        'id' => $project->id,
    ]);
});

it('cannot view a non-existent project', function (): void {
    actingAs($this->adminuser);
    livewire(ViewProject::class, [
        'record' => 999999,
    ]);
})->throws(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

it('cannot create a project with duplicate title', function (): void {
    actingAs($this->adminuser);
    Project::factory()->create([
        'team_id' => $this->team->id,
        'leader_id' => auth()->id(),
        'title' => 'Unique Project Title',
    ]);

    livewire(CreateProject::class)
        ->fillForm([
            'title' => 'Unique Project Title',
            'team_id' => $this->team->id,
            'leader_id' => auth()->id(),
        ])
        ->call('create')
        ->assertHasFormErrors(['title']);
});

it('cannot access project list when not authenticated', function (): void {
    livewire(ListProjects::class)
        ->assertForbidden();
});
