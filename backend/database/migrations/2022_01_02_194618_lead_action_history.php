<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LeadActionHistory extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('lead_action_histories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('short_message');
            $table->foreignId('lead_id')->references('id')->on('leads');
            $table->foreignId('user_id')->nullable()->references('id')->on('users');
            $table->string('status');
            $table->string('changed_status_to')->default('');
            $table->string('action');
            $table->jsonb('values');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('lead_action_histories');
    }
}
