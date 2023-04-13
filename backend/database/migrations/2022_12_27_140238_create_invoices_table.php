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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->float('total')->default(0.00);
            $table->json('items');
            $table->float('total_paid')->default(0.00);
            $table->foreignId('lead_id')->nullable()->references('id')->on('leads');
            $table->string('key');
            $table->unique(['key', 'lead_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
};
