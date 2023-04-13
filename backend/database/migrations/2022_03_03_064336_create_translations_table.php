<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('key');
            $table->text('namespace');
            $table->text('translation');
            $table->string('language');
        });

        $p = Permission::create(['name' => 'create translations', 'system' => 1]);
        $p = Permission::create(['name' => 'update translations', 'system' => 1]);
        $p = Permission::create(['name' => 'delete translations', 'system' => 1]);
        $p = Permission::create(['name' => 'view all translations', 'system' => 1]);

        DB::table('translations')->insert(['language' => 'en', 'namespace' => 'leftmenu', 'key' => 'Add Announcement', 'translation' => 'Add Announcement']);
        DB::table('translations')->insert(['language' => 'en', 'namespace' => 'leftmenu', 'key' => 'User List', 'translation' => 'User List']);
        DB::table('translations')->insert(['language' => 'en', 'namespace' => 'leftmenu', 'key' => 'Add User', 'translation' => 'Add User']);
        DB::table('translations')->insert(['language' => 'en', 'namespace' => 'leftmenu', 'key' => 'Roles', 'translation' => 'Roles']);
        DB::table('translations')->insert(['language' => 'en', 'namespace' => 'leftmenu', 'key' => 'Add Role', 'translation' => 'Add Role']);
        DB::table('translations')->insert(['language' => 'en', 'namespace' => 'leftmenu', 'key' => 'Permissions', 'translation' => 'Permissions']);
        DB::table('translations')->insert(['language' => 'en', 'namespace' => 'leftmenu', 'key' => 'Add Permission', 'translation' => 'Add Permission']);
        DB::table('translations')->insert(['language' => 'en', 'namespace' => 'leftmenu', 'key' => 'Leads List', 'translation' => 'Leads List']);
        DB::table('translations')->insert(['language' => 'en', 'namespace' => 'leftmenu', 'key' => 'Stores List', 'translation' => 'Stores List']);
        DB::table('translations')->insert(['language' => 'en', 'namespace' => 'leftmenu', 'key' => 'Add Store', 'translation' => 'Add Store']);
        DB::table('translations')->insert(['language' => 'en', 'namespace' => 'leftmenu', 'key' => 'Companies List', 'translation' => 'Companies List']);
        DB::table('translations')->insert(['language' => 'en', 'namespace' => 'leftmenu', 'key' => 'Add Company', 'translation' => 'Add Company']);
        DB::table('translations')->insert(['language' => 'en', 'namespace' => 'leftmenu', 'key' => 'Services List', 'translation' => 'Services List']);
        DB::table('translations')->insert(['language' => 'en', 'namespace' => 'leftmenu', 'key' => 'Add Service', 'translation' => 'Add Service']);
        DB::table('translations')->insert(['language' => 'en', 'namespace' => 'leftmenu', 'key' => 'Workflows', 'translation' => 'Workflows']);
        DB::table('translations')->insert(['language' => 'en', 'namespace' => 'leftmenu', 'key' => 'Add Workflow', 'translation' => 'Add Workflow']);
        DB::table('translations')->insert(['language' => 'en', 'namespace' => 'leftmenu', 'key' => 'Lead Actions', 'translation' => 'Lead Actions']);
        DB::table('translations')->insert(['language' => 'en', 'namespace' => 'leftmenu', 'key' => 'Translation List', 'translation' => 'Translation List']);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('translations');
        Permission::where(['name' => 'create translations', 'system' => 1])->first()->delete();
        Permission::where(['name' => 'update translations', 'system' => 1])->first()->delete();
        Permission::where(['name' => 'delete translations', 'system' => 1])->first()->delete();
        Permission::where(['name' => 'view all translations', 'system' => 1])->first()->delete();
    }
};
