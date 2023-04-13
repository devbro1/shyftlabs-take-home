<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

class Template extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('description')->default('');
            $table->string('body')->default('');
            $table->boolean('active')->default(1);
        });

        Permission::create(['name' => 'Update Template', 'system' => 1]);

        $seeder = new \Database\Seeders\TemplateSeeder();
        $seeder->run();
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('templates');
        Permission::where(['name' => 'Update Template'])->first()->delete();
    }
}
