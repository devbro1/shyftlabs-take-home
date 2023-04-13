<?php

use App\Models\User;

test('get a new lead, test action', function () {
    $user = User::role('super-admin')->first();
    $testing_data = generateRandomBase($this);

    $workflow = $testing_data['workflow'];
    $workflow_node_start = $workflow['nodes'][0];
    $workflow_node_end = $workflow['nodes'][1];

    $response = $this->actingAs($user)->get('/api/v1/actions');
    $actions = $response->json()['data'];
    $stale_action = $actions[array_search('Mark as Stale', array_column($actions, 'name'))];
    $stale_action = $this->actingAs($user)->get('/api/v1/actions/'.$stale_action['id'])->json();

    $confirm_action = $actions[array_search('Confirm Action', array_column($actions, 'name'))];
    $confirm_action = $this->actingAs($user)->get('/api/v1/actions/'.$confirm_action['id'])->json();

    $data = [];
    $data['workflow_id'] = $workflow['id'];
    $data['label'] = $workflow_node_start['label'];
    $data['type'] = $workflow_node_start['type'];
    $data['position_x'] = $workflow_node_start['position_x'];
    $data['position_y'] = $workflow_node_start['position_y'];
    $data['actions'] = [];
    $data['actions'][0]['action_id'] = $stale_action['id'];
    $data['actions'][0]['status_to_id'] = null;
    $data['actions'][0]['permission_id'] = null;
    $data['actions'][0]['alternative_name'] = 'Mark as Stale';
    $data['actions'][0]['variables'] = ['duration' => 0];

    $data['actions'][1]['action_id'] = $confirm_action['id'];
    $data['actions'][1]['status_to_id'] = $workflow_node_end['id'];
    $data['actions'][1]['permission_id'] = null;
    $data['actions'][1]['alternative_name'] = 'Move to Complete';
    $data['actions'][1]['variables'] = ['confirmation_message' => 'test message', 'force_action' => false];
    $response = $this->actingAs($user)->put('/api/v1/workflow-nodes/'.$workflow_node_start['id'], $data);

    $testing_data = generateRandomLead($this, $testing_data);
    $this->actingAs($user);
    $response = $this->get('/api/v1/leads/'.$testing_data['lead_id']);
    $response->assertStatus(200);
    $response->assertJson(['stale' => true]);

    // trigger action
    $response = $this->actingAs($testing_data['user_sp'])->get('/api/v1/leads/'.$testing_data['lead_id'].'/actions');
    $response->assertStatus(200);
    $response->assertJsonFragment(['alternative_name' => 'Move to Complete']);
    $action = $response->json()[1];

    $response = $this->actingAs($testing_data['user_sp'])->put('/api/v1/leads/'.$testing_data['lead_id'].'/actions/'.$action['id'], $data);
    $response->assertStatus(200);

    $this->actingAs($user);
    $response = $this->get('/api/v1/leads/'.$testing_data['lead_id']);
    $response->assertStatus(200);
    $response->assertJson(['stale' => false]);
});
