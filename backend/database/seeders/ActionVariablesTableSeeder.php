<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ActionVariablesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        \DB::table('action_variables')->delete();

        \DB::table('action_variables')->insert([
            0 => [
                'name' => 'var1',
                'description' => '',
                'type' => 'text',
                'is_action_variable' => true,
                'is_workflow_node_variable' => true,
                'relation_id' => 1,
                'relation_type' => 'App\\Models\\Action',
                'value' => null,
            ],
            1 => [
                'name' => 'var2',
                'description' => '',
                'type' => 'text',
                'is_action_variable' => true,
                'is_workflow_node_variable' => true,
                'relation_id' => 1,
                'relation_type' => 'App\\Models\\Action',
                'value' => null,
            ],
            2 => [
                'name' => 'var3',
                'description' => '',
                'type' => 'text',
                'is_action_variable' => false,
                'is_workflow_node_variable' => true,
                'relation_id' => 1,
                'relation_type' => 'App\\Models\\Action',
                'value' => null,
            ],
            3 => [
                'name' => 'var4',
                'description' => '',
                'type' => 'text',
                'is_action_variable' => false,
                'is_workflow_node_variable' => true,
                'relation_id' => 1,
                'relation_type' => 'App\\Models\\Action',
                'value' => null,
            ],
            4 => [
                'name' => 'var5',
                'description' => '',
                'type' => 'text',
                'is_action_variable' => true,
                'is_workflow_node_variable' => false,
                'relation_id' => 1,
                'relation_type' => 'App\\Models\\Action',
                'value' => null,
            ],
            5 => [
                'name' => 'var6',
                'description' => '',
                'type' => 'text',
                'is_action_variable' => true,
                'is_workflow_node_variable' => false,
                'relation_id' => 1,
                'relation_type' => 'App\\Models\\Action',
                'value' => null,
            ],
            6 => [
                'name' => 'confirmation_message',
                'description' => 'Message to be shown as part of confirmation',
                'type' => 'text',
                'is_action_variable' => true,
                'is_workflow_node_variable' => true,
                'relation_id' => 3,
                'relation_type' => 'App\\Models\\Action',
                'value' => null,
            ],
            7 => [
                'name' => 'date_type',
                'description' => 'What to save the date for',
                'type' => 'text',
                'is_action_variable' => false,
                'is_workflow_node_variable' => true,
                'relation_id' => 4,
                'relation_type' => 'App\\Models\\Action',
                'value' => null,
            ],
            8 => [
                'name' => 'has_time',
                'description' => 'if this date include time too',
                'type' => 'text',
                'is_action_variable' => false,
                'is_workflow_node_variable' => true,
                'relation_id' => 4,
                'relation_type' => 'App\\Models\\Action',
                'value' => null,
            ],
            9 => [
                'name' => 'confirmation_message',
                'description' => 'Message to be shown as part of confirmation',
                'type' => 'text',
                'is_action_variable' => true,
                'is_workflow_node_variable' => true,
                'relation_id' => 6,
                'relation_type' => 'App\\Models\\Action',
                'value' => null,
            ],
            10 => [
                'name' => 'force_action',
                'description' => 'force user to do this action before they can view lead',
                'type' => 'text',
                'is_action_variable' => true,
                'is_workflow_node_variable' => true,
                'relation_id' => 3,
                'relation_type' => 'App\\Models\\Action',
                'value' => null,
            ],
            11 => [
                'name' => 'force_action',
                'description' => 'force user to do this action before they can view lead',
                'type' => 'text',
                'is_action_variable' => true,
                'is_workflow_node_variable' => true,
                'relation_id' => 4,
                'relation_type' => 'App\\Models\\Action',
                'value' => null,
            ],
        ]);
    }
}
