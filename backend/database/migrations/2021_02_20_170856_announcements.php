<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

class Announcements extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title');
            $table->string('body');
        });

        $perm = Permission::create(['name' => 'Create Announcement', 'system' => 1]);
        $perm = Permission::create(['name' => 'Update Announcement', 'system' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('announcements');

        Permission::where(['name' => 'Create Announcement'])->first()->delete();
        Permission::where(['name' => 'Update Announcement'])->first()->delete();
    }
}
