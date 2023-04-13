<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('actions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('backend_uri')->default('');
            $table->string('frontend_uri')->default('');
            $table->string('name');
            $table->boolean('active')->default(0);
            $table->string('type')->default('api');
            $table->string('action_class')->nullable();
        });

        Schema::create('action_variables', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->default('');
            $table->string('type')->default('text');
            $table->boolean('is_action_variable')->default(true);
            $table->boolean('is_workflow_node_variable')->default(true);
            $table->unsignedBigInteger('relation_id');
            $table->string('relation_type'); // actions action_workflow_node
            $table->string('value')->nullable()->default(null);
        });

        Schema::create('action_workflow_node', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('alternative_name');
            $table->foreignId('status_to_id')->nullable()->references('id')->on('workflow_nodes');
            $table->foreignId('action_id')->references('id')->on('actions');
            $table->foreignId('workflow_node_id')->references('id')->on('workflow_nodes');
            $table->foreignId('permission_id')->nullable()->references('id')->on('permissions');
            $table->jsonb('variables')->default('{}');
        });

        Artisan::call(
            'db:seed',
            [
                '--class' => 'ActionSeeder',
                '--force' => true, ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('action_workflow_node');
        Schema::dropIfExists('action_variables');
        Schema::dropIfExists('actions');
    }
}
