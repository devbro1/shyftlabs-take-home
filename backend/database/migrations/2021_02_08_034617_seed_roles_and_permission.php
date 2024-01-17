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
        Permission::create(['name' => 'configuration', 'system' => 1]);
        Permission::create(['name' => 'debug', 'system' => 1]);
        Permission::create(['name' => 'update templates', 'system' => 1]);
        Permission::create(['name' => 'add user', 'system' => 1]);
        Permission::create(['name' => 'update user', 'system' => 1]);
        Permission::create(['name' => 'view users', 'system' => 1]);
        Permission::create(['name' => 'manage roles', 'system' => 1]);
        Permission::create(['name' => 'create permission', 'system' => 1]);
        Permission::create(['name' => 'update permission', 'system' => 1]);
        Permission::create(['name' => 'create role', 'system' => 1]);
        Permission::create(['name' => 'update role', 'system' => 1]);

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
