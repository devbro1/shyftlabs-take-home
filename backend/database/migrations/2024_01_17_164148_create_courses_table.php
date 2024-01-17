<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
        });

        Permission::create(['name' => 'view courses', 'system' => 1]);
        Permission::create(['name' => 'create courses', 'system' => 1]);
        Permission::create(['name' => 'update courses', 'system' => 1]);
        Permission::create(['name' => 'delete courses', 'system' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Permission::where(['name' => 'view courses', 'system' => 1])->first()->delete();
        Permission::where(['name' => 'create courses', 'system' => 1])->first()->delete();
        Permission::where(['name' => 'update courses', 'system' => 1])->first()->delete();
        Permission::where(['name' => 'delete courses', 'system' => 1])->first()->delete();

        Schema::dropIfExists('courses');
    }
};
