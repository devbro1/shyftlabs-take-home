<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->boolean('active');
            $table->string('address');
            $table->string('city');
            $table->string('province_code')->references('code')->on('provinces')->nullable();
            $table->string('country_code')->references('code')->on('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('phone1')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('logo_file_id')->nullable();
        });

        Schema::create('company_user', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('company_id')->references('id')->on('companies');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->string('position');
        });

        $p = Permission::create(['name' => 'Create Company', 'system' => 1]);
        $p = Permission::create(['name' => 'Update Company', 'system' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('company_user');
        Schema::dropIfExists('companies');
    }
}
