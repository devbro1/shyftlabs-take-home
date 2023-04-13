<?php

use Illuminate\Database\Migrations\Migration;

class InstallPassport extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Artisan::call('passport:install', []);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
    }
}
