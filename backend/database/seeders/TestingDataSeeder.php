<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestingDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(StoresTableSeeder::class);

        $this->call(CompanySeeder::class);
        $this->call(WorkflowsTableSeeder::class);
        $this->call(WorkflowNodesTableSeeder::class);
        $this->call(WorkflowEdgesTableSeeder::class);
        $this->call(ActionWorkflowNodeTableSeeder::class);
        $this->call(ServiceAvailabilitiesTableSeeder::class);

        $this->command->info('Fixing database sequences');
        DB::select(DB::raw("SELECT setval('workflows_id_seq', (select max(id) from workflows))"));
        DB::select(DB::raw("SELECT setval('workflow_nodes_id_seq', (select max(id) from workflow_nodes))"));
        DB::select(DB::raw("SELECT setval('workflow_edges_id_seq', (select max(id) from workflow_edges))"));
        DB::select(DB::raw("SELECT setval('action_workflow_node_id_seq', (select max(id) from action_workflow_node))"));
        $this->command->info('Finished with database sequences');
    }
}
