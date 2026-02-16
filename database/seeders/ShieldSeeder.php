<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $tenants = '[{"id":1,"team_id":1,"leader_id":2,"studydesign_id":1,"identifier":"odit","title":"Similique itaque sit velit ducimus et.","description":"Ipsum provident odio ratione sed reiciendis ut eligendi. Sint architecto qui ab reprehenderit odio. Dolorem consequatur sint voluptatibus quo est. Veritatis dolores et et accusamus rem tempore.","submission_date":"1971-12-25","public_release_date":null,"subjectID_prefix":"LVKZ","subjectID_digits":4,"storageDesignation":"eligendi","last_subject_number":9,"redcapProject_id":null,"created_at":"2026-02-04T13:40:47.000000Z","updated_at":"2026-02-16T12:13:43.000000Z"},{"id":2,"team_id":1,"leader_id":2,"studydesign_id":0,"identifier":"modi","title":"Voluptate aperiam voluptas unde ipsam.","description":"Aut quia doloribus sed fugiat in aperiam quo. Eius placeat sit rerum atque repellendus odit. Saepe quas voluptate omnis tempore perspiciatis et ullam.","submission_date":"2012-04-28","public_release_date":null,"subjectID_prefix":"WGAW","subjectID_digits":5,"storageDesignation":"cumque","last_subject_number":9,"redcapProject_id":null,"created_at":"2026-02-04T13:40:47.000000Z","updated_at":"2026-02-04T13:40:47.000000Z"},{"id":3,"team_id":1,"leader_id":2,"studydesign_id":1,"identifier":"molestiae","title":"Ad numquam temporibus.","description":"Quo voluptates pariatur deserunt sit ducimus deleniti. Delectus ratione qui incidunt sed debitis. Et facilis illum sint laboriosam distinctio.","submission_date":"1988-03-02","public_release_date":null,"subjectID_prefix":"ZLLFK","subjectID_digits":4,"storageDesignation":"asperiores","last_subject_number":9,"redcapProject_id":null,"created_at":"2026-02-04T13:40:47.000000Z","updated_at":"2026-02-16T10:25:50.000000Z"}]';
        $users = '[]';
        $userTenantPivot = '[]';
        $rolesWithPermissions = '[{"name":"Admin","guard_name":"web","permissions":[],"project_id":1},{"name":"Member","guard_name":"web","permissions":[],"project_id":1},{"name":"Admin","guard_name":"web","permissions":[],"project_id":2},{"name":"Member","guard_name":"web","permissions":[],"project_id":2},{"name":"Admin","guard_name":"web","permissions":[],"project_id":3},{"name":"Member","guard_name":"web","permissions":[],"project_id":3}]';
        $directPermissions = '[]';

        // 1. Seed tenants first (if present)
        if (! blank($tenants) && $tenants !== '[]') {
            static::seedTenants($tenants);
        }

        // 2. Seed roles with permissions
        static::makeRolesWithPermissions($rolesWithPermissions);

        // 3. Seed direct permissions
        static::makeDirectPermissions($directPermissions);

        // 4. Seed users with their roles/permissions (if present)
        if (! blank($users) && $users !== '[]') {
            static::seedUsers($users);
        }

        // 5. Seed user-tenant pivot (if present)
        if (! blank($userTenantPivot) && $userTenantPivot !== '[]') {
            static::seedUserTenantPivot($userTenantPivot);
        }

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function seedTenants(string $tenants): void
    {
        if (blank($tenantData = json_decode($tenants, true))) {
            return;
        }

        $tenantModel = 'App\Models\Project';
        if (blank($tenantModel)) {
            return;
        }

        foreach ($tenantData as $tenant) {
            $tenantModel::firstOrCreate(
                ['id' => $tenant['id']],
                $tenant
            );
        }
    }

    protected static function seedUsers(string $users): void
    {
        if (blank($userData = json_decode($users, true))) {
            return;
        }

        $userModel = 'App\Models\User';
        $tenancyEnabled = true;

        foreach ($userData as $data) {
            // Extract role/permission data before creating user
            $roles = $data['roles'] ?? [];
            $permissions = $data['permissions'] ?? [];
            $tenantRoles = $data['tenant_roles'] ?? [];
            $tenantPermissions = $data['tenant_permissions'] ?? [];
            unset($data['roles'], $data['permissions'], $data['tenant_roles'], $data['tenant_permissions']);

            $user = $userModel::firstOrCreate(
                ['email' => $data['email']],
                $data
            );

            // Handle tenancy mode - sync roles/permissions per tenant
            if ($tenancyEnabled && (! empty($tenantRoles) || ! empty($tenantPermissions))) {
                foreach ($tenantRoles as $tenantId => $roleNames) {
                    $contextId = $tenantId === '_global' ? null : $tenantId;
                    setPermissionsTeamId($contextId);
                    $user->syncRoles($roleNames);
                }

                foreach ($tenantPermissions as $tenantId => $permissionNames) {
                    $contextId = $tenantId === '_global' ? null : $tenantId;
                    setPermissionsTeamId($contextId);
                    $user->syncPermissions($permissionNames);
                }
            } else {
                // Non-tenancy mode
                if (! empty($roles)) {
                    $user->syncRoles($roles);
                }

                if (! empty($permissions)) {
                    $user->syncPermissions($permissions);
                }
            }
        }
    }

    protected static function seedUserTenantPivot(string $pivot): void
    {
        if (blank($pivotData = json_decode($pivot, true))) {
            return;
        }

        $pivotTable = 'project_user';
        if (blank($pivotTable)) {
            return;
        }

        foreach ($pivotData as $row) {
            $uniqueKeys = [];

            if (isset($row['user_id'])) {
                $uniqueKeys['user_id'] = $row['user_id'];
            }

            $tenantForeignKey = 'project_id';
            if (! blank($tenantForeignKey) && isset($row[$tenantForeignKey])) {
                $uniqueKeys[$tenantForeignKey] = $row[$tenantForeignKey];
            }

            if (! empty($uniqueKeys)) {
                DB::table($pivotTable)->updateOrInsert($uniqueKeys, $row);
            }
        }
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            return;
        }

        /** @var \Illuminate\Database\Eloquent\Model $roleModel */
        $roleModel = Utils::getRoleModel();
        /** @var \Illuminate\Database\Eloquent\Model $permissionModel */
        $permissionModel = Utils::getPermissionModel();

        $tenancyEnabled = true;
        $teamForeignKey = 'project_id';

        foreach ($rolePlusPermissions as $rolePlusPermission) {
            $tenantId = $rolePlusPermission[$teamForeignKey] ?? null;

            // Set tenant context for role creation and permission sync
            if ($tenancyEnabled) {
                setPermissionsTeamId($tenantId);
            }

            $roleData = [
                'name' => $rolePlusPermission['name'],
                'guard_name' => $rolePlusPermission['guard_name'],
            ];

            // Include tenant ID in role data (can be null for global roles)
            if ($tenancyEnabled && ! blank($teamForeignKey)) {
                $roleData[$teamForeignKey] = $tenantId;
            }

            $role = $roleModel::firstOrCreate($roleData);

            if (! blank($rolePlusPermission['permissions'])) {
                $permissionModels = collect($rolePlusPermission['permissions'])
                    ->map(fn ($permission) => $permissionModel::firstOrCreate([
                        'name' => $permission,
                        'guard_name' => $rolePlusPermission['guard_name'],
                    ]))
                    ->all();

                $role->syncPermissions($permissionModels);
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (blank($permissions = json_decode($directPermissions, true))) {
            return;
        }

        /** @var \Illuminate\Database\Eloquent\Model $permissionModel */
        $permissionModel = Utils::getPermissionModel();

        foreach ($permissions as $permission) {
            if ($permissionModel::whereName($permission['name'])->doesntExist()) {
                $permissionModel::create([
                    'name' => $permission['name'],
                    'guard_name' => $permission['guard_name'],
                ]);
            }
        }
    }
}
