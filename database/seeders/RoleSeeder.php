<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::each(function (Project $project): void {
            Role::create([
                'name' => 'admin',
                'guard_name' => 'web',
                'project_id' => $project->id,
            ]);
            Role::create([
                'name' => 'member',
                'guard_name' => 'web',
                'project_id' => $project->id,
            ]);
        });
    }
}
