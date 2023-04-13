<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvinceTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('provinces', function (Blueprint $table) {
            $table->string('code');
            $table->primary('code');
            $table->string('abbreviation');
            $table->string('name');
            $table->string('country_code')->references('code')->on('country');
        });

        Artisan::call(
            'db:seed',
            [
                '--class' => 'ProvinceSeeder',
                '--force' => true,
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('provinces');
    }
}
