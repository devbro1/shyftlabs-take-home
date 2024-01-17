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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('first_name');
            $table->string('family_name');
            $table->string('email');
            $table->date('date_of_birth');
        });


        Permission::create(['name' => 'view students', 'system' => 1]);
        Permission::create(['name' => 'create students', 'system' => 1]);
        Permission::create(['name' => 'update students', 'system' => 1]);
        Permission::create(['name' => 'delete students', 'system' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Permission::where(['name' => 'view students', 'system' => 1])->first()->delete();
        Permission::where(['name' => 'create students', 'system' => 1])->first()->delete();
        Permission::where(['name' => 'update students', 'system' => 1])->first()->delete();
        Permission::where(['name' => 'delete students', 'system' => 1])->first()->delete();

        Schema::dropIfExists('students');
    }
};
