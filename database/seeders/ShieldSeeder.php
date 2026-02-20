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
        $users = '[{"id":1,"username":"admin","firstname":"System","lastname":"Administrator","system_role":0,"team_id":null,"team_role":null,"email":"admin@example.com","email_verified_at":null,"telephone":null,"homesite":"System","active":true,"created_at":"2026-02-04T13:40:45.000000Z","updated_at":"2026-02-04T13:40:45.000000Z","fullname":"System Administrator","tenant_roles":{"1":["Member"],"3":["Member"]}},{"id":2,"username":"gvds","firstname":"Gian","lastname":"van der Spuy","system_role":0,"team_id":1,"team_role":"Admin","email":"gvds@sun.ac.za","email_verified_at":"2026-02-04T13:40:46.000000Z","telephone":"27 (21) 938-9949","homesite":"SU_ZA","active":true,"created_at":"2026-02-04T13:40:46.000000Z","updated_at":"2026-02-04T13:40:46.000000Z","fullname":"Gian van der Spuy","tenant_roles":{"1":["Admin"],"2":["Admin"],"3":["Admin"]}},{"id":3,"username":"visser.belinda","firstname":"Jonathan","lastname":"Ellis","system_role":1,"team_id":1,"team_role":"Member","email":"ndaba.sean@example.org","email_verified_at":"2026-02-04T13:40:46.000000Z","telephone":"21 (12) 393-9242","homesite":"MRC_GM","active":true,"created_at":"2026-02-04T13:40:46.000000Z","updated_at":"2026-02-04T13:40:46.000000Z","fullname":"Jonathan Ellis","tenant_roles":{"1":["Member"],"2":["Member"]}},{"id":4,"username":"nicola84","firstname":"Gloria","lastname":"Butler","system_role":1,"team_id":1,"team_role":"Member","email":"amartin@example.net","email_verified_at":"2026-02-04T13:40:46.000000Z","telephone":"79 (05) 824-0043","homesite":"UCT_ZA","active":true,"created_at":"2026-02-04T13:40:46.000000Z","updated_at":"2026-02-04T13:40:46.000000Z","fullname":"Gloria Butler","tenant_roles":[]},{"id":5,"username":"fourie.xolani","firstname":"Unathi","lastname":"Jonker","system_role":1,"team_id":1,"team_role":"Member","email":"alfred.vosloo@example.com","email_verified_at":"2026-02-04T13:40:46.000000Z","telephone":"53 (96) 793-4832","homesite":"UCT_ZA","active":true,"created_at":"2026-02-04T13:40:46.000000Z","updated_at":"2026-02-04T13:40:46.000000Z","fullname":"Unathi Jonker","tenant_roles":{"1":["Member"],"2":["Member"],"3":["Member"]}},{"id":6,"username":"denise00","firstname":"Megan","lastname":"Hendricks","system_role":1,"team_id":1,"team_role":"Member","email":"vanessa.madlala@example.com","email_verified_at":"2026-02-04T13:40:46.000000Z","telephone":"22 (06) 021-3694","homesite":"UCT_ZA","active":true,"created_at":"2026-02-04T13:40:46.000000Z","updated_at":"2026-02-04T13:40:46.000000Z","fullname":"Megan Hendricks","tenant_roles":{"2":["Member"],"3":["Member"]}}]';
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
