<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Scopes\ProjectScope;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::withoutGlobalScope(ProjectScope::class)->each(function (Project $project): void {
            Role::create([
                'name' => 'Admin',
                'guard_name' => 'web',
                'project_id' => $project->id,
            ]);
            Role::create([
                'name' => 'Member',
                'guard_name' => 'web',
                'project_id' => $project->id,
            ]);
        });
    }
}
