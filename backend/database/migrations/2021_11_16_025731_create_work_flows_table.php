<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkflowsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('workflows', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('description')->default('');
            $table->boolean('active')->default(true);
        });

        Schema::create('workflow_nodes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('workflow_id');
            $table->foreign('workflow_id')->references('id')->on('workflows');
            $table->integer('position_x');
            $table->integer('position_y');
            $table->string('label');
            $table->string('type');
        });

        Schema::create('workflow_edges', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name')->default('');
            $table->unsignedBigInteger('workflow_id');
            $table->unsignedBigInteger('source_id');
            $table->unsignedBigInteger('target_id');
            $table->foreign('workflow_id')->references('id')->on('workflows');
            $table->foreign('source_id')->references('id')->on('workflow_nodes')->onDelete('cascade');
            $table->foreign('target_id')->references('id')->on('workflow_nodes')->onDelete('cascade');
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('workflow_id');
            $table->foreign('workflow_id')->references('id')->on('workflows');
            $table->foreign('status_id')->references('id')->on('workflow_nodes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('workflow_id');
            $table->dropColumn('status_id');
        });
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('workflow_id');
        });
        Schema::dropIfExists('workflow_edges');
        Schema::dropIfExists('workflow_nodes');
        Schema::dropIfExists('workflows');
    }
}
