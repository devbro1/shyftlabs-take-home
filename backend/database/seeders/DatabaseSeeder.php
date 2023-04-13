<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(TemplateSeeder::class);
        $this->call(StoresTableSeeder::class);
        $this->call(ServiceAvailabilitiesTableSeeder::class);

        $this->call(ActionsTableSeeder::class);
        $this->call(ActionVariablesTableSeeder::class);
        $this->call(WorkflowsTableSeeder::class);
        $this->call(WorkflowNodesTableSeeder::class);
        $this->call(WorkflowEdgesTableSeeder::class);
        $this->call(ActionWorkflowNodeTableSeeder::class);
    }
}
