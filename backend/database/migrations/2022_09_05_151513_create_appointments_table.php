<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('owner_id')->references('id')->on('users');
            $table->foreignId('created_by')->references('id')->on('users');
            $table->datetime('dt_start');
            $table->datetime('dt_end');
            $table->json('services');
            $table->json('stores');
            $table->foreignId('lead_id')->nullable()->references('id')->on('leads');
        });

        $p = Permission::create(['name' => 'manage self appointments', 'system' => 1]);
        $p = Permission::create(['name' => 'manage all appointments', 'system' => 1]);
        $p = Permission::create(['name' => 'have appointments', 'system' => 1]);
        $p = Permission::create(['name' => 'manage company appointments', 'system' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Permission::where(['name' => 'create translations', 'system' => 1])->first()->delete();
        Permission::where(['name' => 'manage self appointments', 'system' => 1])->first()->delete();
        Permission::where(['name' => 'manage all appointments', 'system' => 1])->first()->delete();
        Permission::where(['name' => 'have appointments', 'system' => 1])->first()->delete();
        Permission::where(['name' => 'manage company appointments', 'system' => 1])->first()->delete();
        Schema::dropIfExists('appointments');
    }
};
