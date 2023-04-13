<?php

use App\Models\User;

test('get a new lead, add action, test invoice feature', function () {
    $user = User::role('super-admin')->first();
    $testing_data = generateRandomBase($this);

    $workflow = $testing_data['workflow'];
    $workflow_node_start = $workflow['nodes'][0];
    $workflow_node_end = $workflow['nodes'][1];

    $response = $this->actingAs($user)->get('/api/v1/actions');
    $actions = $response->json()['data'];
    $invoice_action = $actions[array_search('Fill Invoice', array_column($actions, 'name'))];
    $invoice_action = $this->actingAs($user)->get('/api/v1/actions/'.$invoice_action['id'])->json();

    $data = [];
    $data['workflow_id'] = $workflow['id'];
    $data['label'] = $workflow_node_start['label'];
    $data['type'] = $workflow_node_start['type'];
    $data['position_x'] = $workflow_node_start['position_x'];
    $data['position_y'] = $workflow_node_start['position_y'];
    $data['actions'] = [];
    $data['actions'][0]['action_id'] = $invoice_action['id'];
    $data['actions'][0]['status_to_id'] = $workflow_node_end['id'];
    $data['actions'][0]['permission_id'] = null;
    $data['actions'][0]['alternative_name'] = 'Fill Invoice';
    $data['actions'][0]['variables'] = ['key' => 'MAIN',
        'total_required' => true,
        'item_fields' => 'quantity,description,subtotal',
        'pre_fill' => '', ];
    $response = $this->actingAs($user)->put('/api/v1/workflow-nodes/'.$workflow_node_start['id'], $data);

    $testing_data = generateRandomLead($this, $testing_data);
    $this->actingAs($user);
    $response = $this->get('/api/v1/leads/'.$testing_data['lead_id']);
    $response->assertStatus(200);

    // trigger action
    $response = $this->actingAs($testing_data['user_sp'])->get('/api/v1/leads/'.$testing_data['lead_id'].'/actions');
    $response->assertStatus(200);
    $response->assertJsonFragment(['alternative_name' => 'Fill Invoice']);
    $action = $response->json()[0];

    // negative total
    $data = ['total' => -941.00];
    $data['items'] = [];
    $data['items'][] = ['quantity' => 1, 'subtotal' => 1.00, 'description' => 'item 1'];
    $data['items'][] = ['quantity' => 2, 'subtotal' => 20.00, 'description' => 'item 2'];
    $data['items'][] = ['quantity' => 3, 'subtotal' => 300.00, 'description' => 'item 3'];
    $response = $this->actingAs($testing_data['user_sp'])->put('/api/v1/leads/'.$testing_data['lead_id'].'/actions/'.$action['id'], $data);
    $response->assertStatus(422);

    $data = ['total' => 941.01];
    $data['items'] = [];
    $data['items'][] = ['quantity' => 1, 'subtotal' => 1.00, 'description' => 'item 1', 'extra_item' => 'bad'];
    $data['items'][] = ['quantity' => 2, 'subtotal' => 20.00, 'description' => 'item 2'];
    $data['items'][] = ['quantity' => 3, 'subtotal' => 300.00, 'description' => 'item 3'];
    $response = $this->actingAs($testing_data['user_sp'])->put('/api/v1/leads/'.$testing_data['lead_id'].'/actions/'.$action['id'], $data);
    $response->assertStatus(422);

    $data = ['total' => 941.01];
    $data['items'] = [];
    $data['items'][] = ['quantity' => 1, 'subtotal' => 1.00]; // missing
    $data['items'][] = ['quantity' => 2, 'subtotal' => 20.00, 'description' => 'item 2'];
    $data['items'][] = ['quantity' => 3, 'subtotal' => 300.00, 'description' => 'item 3'];
    $response = $this->actingAs($testing_data['user_sp'])->put('/api/v1/leads/'.$testing_data['lead_id'].'/actions/'.$action['id'], $data);
    $response->assertStatus(422);

    $data = ['total' => 941.01];
    $data['items'] = [];
    $data['items'][] = ['quantity' => 1, 'subtotal' => 1.00, 'description' => 'item 1'];
    $data['items'][] = ['quantity' => 2, 'subtotal' => 20.00, 'description' => 'item 2'];
    $data['items'][] = ['quantity' => 3, 'subtotal' => 300.00, 'description' => 'item 3'];
    $response = $this->actingAs($testing_data['user_sp'])->put('/api/v1/leads/'.$testing_data['lead_id'].'/actions/'.$action['id'], $data);
    $response->assertStatus(200);

    $this->actingAs($user);
    $response = $this->get('/api/v1/leads/'.$testing_data['lead_id']);
    $response->assertStatus(200);
    $response->assertJsonStructure(['invoices']);
    $response->assertJsonFragment(['total' => '941.01']);
    $response->assertJsonFragment(['description' => 'item 1']);
    $response->assertJsonFragment(['description' => 'item 2']);
    $response->assertJsonFragment(['description' => 'item 3']);
    $response->assertJsonFragment(['status_id' => $workflow_node_end['id']]);
});
