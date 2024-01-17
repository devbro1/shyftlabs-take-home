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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('course_id')->references('id')->on('courses');
            $table->foreignId('student_id')->references('id')->on('students');
            $table->string("score");
        });

        Permission::create(['name' => 'view results', 'system' => 1]);
        Permission::create(['name' => 'create results', 'system' => 1]);
        Permission::create(['name' => 'update results', 'system' => 1]);
        Permission::create(['name' => 'delete results', 'system' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Permission::where(['name' => 'view results', 'system' => 1])->first()->delete();
        Permission::where(['name' => 'create results', 'system' => 1])->first()->delete();
        Permission::where(['name' => 'update results', 'system' => 1])->first()->delete();
        Permission::where(['name' => 'delete results', 'system' => 1])->first()->delete();

        Schema::dropIfExists('results');
    }
};
