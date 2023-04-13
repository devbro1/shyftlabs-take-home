<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class CreateServiceAvailabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('service_availabilities', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('store_id')->references('id')->on('stores');
            $table->foreignId('company_id')->references('id')->on('companies');
            $table->foreignId('service_id')->references('id')->on('services');
            $table->foreignId('workflow_id')->references('id')->on('workflows');
            $table->index(['store_id', 'company_id', 'service_id']);
            $table->unique(['store_id', 'company_id', 'service_id']);
        });

        Schema::create('lead_owners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->references('id')->on('leads');
            $table->foreignId('provider_id')->references('id')->on('users');
            $table->boolean('main_provider')->nullable(true);
            $table->unique(['lead_id', 'main_provider']);
        });

        DB::statement('alter table lead_owners add check (main_provider = true OR main_provider is null)'); // there can only be ONE main lead_owner

        Permission::create(['name' => 'Service Lead', 'system' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Permission::where(['name' => 'Service Lead'])->delete();
        Schema::dropIfExists('lead_owners');
        Schema::dropIfExists('service_availabilities');
    }
}
