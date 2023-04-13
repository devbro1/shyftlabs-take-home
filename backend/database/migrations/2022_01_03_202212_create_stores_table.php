<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->boolean('active')->default(false);
            $table->string('store_no');
            $table->string('name');

            $table->string('address');
            $table->string('city');
            $table->string('province_code');
            $table->string('country_code');
            $table->string('postal_code');

            $table->float('longitude')->nullable();
            $table->float('latitude')->nullable();
            $table->float('coverage_radius')->default('100');
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->foreignId('store_id')->references('id')->on('stores');
        });

        Permission::create(['name' => 'Create Store', 'system' => 1]);
        Permission::create(['name' => 'Update Store', 'system' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('store_id');
        });
        Schema::dropIfExists('stores');
    }
}
