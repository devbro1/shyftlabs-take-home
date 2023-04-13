<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePostalCodesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('postal_codes', function (Blueprint $table) {
            $table->string('code');
            $table->primary('code');
            $table->string('city');
            $table->string('province_code');
            $table->foreign('province_code')->references('code')->on('provinces');
            $table->string('country_code');
            $table->foreign('country_code')->references('code')->on('countries');
            $table->unsignedMediumInteger('time_zone_id');
            $table->double('latitude');
            $table->double('longitude');
        });

        DB::statement('create extension IF NOT EXISTS cube');
        DB::statement('create extension IF NOT EXISTS earthdistance');

        Artisan::call(
            'db:seed',
            [
                '--class' => 'PostalCodeSeeder',
                '--force' => true,
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('postal_codes');
    }
}
