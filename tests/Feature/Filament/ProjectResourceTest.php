<?php


use App\Models\Project;
use App\Filament\Resources\Projects\ProjectResource;
use App\Filament\Resources\Projects\Pages\CreateProject;
use App\Filament\Resources\Projects\Pages\EditProject;
use App\Filament\Resources\Projects\Pages\ListProjects;
use App\Filament\Resources\Projects\Pages\ViewProject;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Livewire\livewire;


it('can render page', function () {

    livewire(ListProjects::class)
        ->assertSuccessful();
});

// it('can list projects in the table', function () {

//     $user = User::factory()
//         ->create();
//     $team = Team::factory()
//         ->create([
//             'leader_id' => $user->id,
//         ]);
//     $user->update([
//         'team_id' => $team->id,
//         'team_role' => 'Admin',
//     ]);

//     $projects = Project::factory()->count(3)->create([
//         'team_id' => $team->id,
//         'leader_id' => $user->id
//     ]);

//     Livewire::actingAs($user)
//         ->test(ListProjects::class)
//         ->assertCanSeeTableRecords($projects)
//         ->searchTable($projects->first()->name)
//         ->assertCanSeeTableRecords($projects->take(1))
//         ->assertCanNotSeeTableRecords($projects->skip(1));
// });

// it('can create a project', function () {
//     $data = Project::factory()->make()->toArray();

//     livewire(CreateProject::class)
//         ->fillForm($data)
//         ->call('create')
//         ->assertNotified()
//         ->assertRedirect();

//     assertDatabaseHas(Project::class, [
//         'name' => $data['name'],
//     ]);
// });

// it('can view a project', function () {
//     $project = Project::factory()->create();

//     livewire(ViewProject::class, [
//         'record' => $project->getKey(),
//     ])->assertOk()
//         ->assertSee($project->name);
// });

// it('can edit a project', function () {
//     $project = Project::factory()->create();
//     $newName = 'Updated Project Name';

//     livewire(EditProject::class, [
//         'record' => $project->getKey(),
//     ])->fillForm([
//         'name' => $newName,
//     ])->call('save')
//         ->assertNotified()
//         ->assertRedirect();

//     assertDatabaseHas(Project::class, [
//         'id' => $project->id,
//         'name' => $newName,
//     ]);
// });
