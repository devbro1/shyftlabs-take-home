<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('lead_dates', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('lead_id')->references('id')->on('leads');
            $table->string('date_type');
            $table->datetime('dt');
            $table->index(['lead_id', 'date_type']);
            $table->unique(['lead_id', 'date_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('lead_dates');
    }
};
