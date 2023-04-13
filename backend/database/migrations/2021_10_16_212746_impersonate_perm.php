<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;

class ImpersonatePerm extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $p = Permission::create(['name' => 'impersonate', 'system' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $p = Permission::where('name', 'impersonate')->first();
        $p->delete();
    }
}
