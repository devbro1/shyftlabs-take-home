<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SeedRolesAndPermission extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'Configuration', 'system' => 1]);
        Permission::create(['name' => 'Debug', 'system' => 1]);
        Permission::create(['name' => 'Update Templates', 'system' => 1]);
        Permission::create(['name' => 'Add User', 'system' => 1]);
        Permission::create(['name' => 'Update User', 'system' => 1]);
        Permission::create(['name' => 'View Users', 'system' => 1]);
        Permission::create(['name' => 'Manage Roles', 'system' => 1]);
        Permission::create(['name' => 'Create Permission', 'system' => 1]);
        Permission::create(['name' => 'Update Permission', 'system' => 1]);
        Permission::create(['name' => 'Create Role', 'system' => 1]);
        Permission::create(['name' => 'Update Role', 'system' => 1]);

        // this can be done as separate statements
        $role = Role::create(['name' => 'super-admin', 'description' => 'Super Administrator']);
        $role = Role::create(['name' => 'administrator', 'description' => 'Administrator']);
        $role = Role::create(['name' => 'volunteer', 'description' => 'Volunteer']);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
    }
}
