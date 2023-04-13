<?php

use App\Models\User;
use App\Models\Appointment;

use function Pest\Faker\faker;

test('generic appointment create, list, delete, relist', function () {
    Appointment::truncate();

    $user = User::permission('Create Announcement')->first();
    $next_monday = new \DateTime('next monday');
    $next_tuesday = new \DateTime('next monday');
    $next_tuesday->modify('2 days');
    $next_thursday = new \DateTime('next monday');
    $next_thursday->modify('4 days');
    $next_friday = new \DateTime('next monday');
    $next_friday->modify('5 days');
    $data = [];
    $data['date_start'] = $next_monday->format('Y-m-d');
    $data['date_end'] = $next_friday->format('Y-m-d');
    $data['time_start'] = '02:00';
    $data['time_end'] = '09:00';
    $data['appointment_duration'] = '01:00';
    $data['appointment_padding'] = '00:30';
    $data['services'][] = 1;
    $data['stores'][] = 1;
    $response = $this->actingAs($user)->post('/api/v1/appointments', $data);
    $response->assertStatus(200);
    $response->assertJsonCount(30, 'data.*');

    $response = $this->actingAs($user)->get('/api/v1/users/1/appointments?from='.$next_tuesday->format('Y-m-d').'&to='.$next_thursday->format('Y-m-d').'');
    $response->assertStatus(200);
    $response->assertJson(['total' => 30]);

    $id_to_delete = $response->json()['data'][2]['id'];

    $response = $this->actingAs($user)->delete('/api/v1/appointments/'.$id_to_delete);
    $response->assertStatus(200);

    $response = $this->actingAs($user)->get('/api/v1/users/1/appointments?from='.$next_tuesday->format('Y-m-d').'&to='.$next_thursday->format('Y-m-d').'');
    $response->assertStatus(200);
    $response->assertJson(['total' => 29]);
});

it('Create a new user, give permission, add appointment for the user', function () {
    $user = User::role('super-admin')->first();

    // Create Users
    $data = [];
    $data['username'] = faker()->userName();
    $data['full_name'] = faker()->name();
    $data['email'] = faker()->email();
    $data['permissions'] = ['have appointments', 'Service Lead', 'manage self appointments'];

    $response = $this->actingAs($user)->post('/api/v1/users', $data);
    $response->assertStatus(200);
    $sp_id = $response->json()['data']['id'];
    $user_sp = User::find($sp_id);

    $data = [];
    $data['username'] = faker()->userName();
    $data['full_name'] = faker()->name();
    $data['email'] = faker()->email();
    $data['permissions'] = ['have appointments', 'Service Lead', 'manage self appointments'];

    $response = $this->actingAs($user)->post('/api/v1/users', $data);
    $response->assertStatus(200);
    $emp_id = $response->json()['data']['id'];

    // Create Company
    $data = [];
    $data['active'] = 1;
    $data['address'] = faker()->streetAddress();
    $data['city'] = faker()->city();
    $data['province_code'] = 'ON';
    $data['country_code'] = 'CA';
    $data['postal_code'] = 'L7C2A5';
    $data['phone1'] = faker()->phoneNumber();
    $data['email'] = faker()->email();
    $data['name'] = faker()->company();
    $data['owner_ids'] = [$sp_id];
    $data['employee_ids'] = [$emp_id];

    $response = $this->actingAs($user)->post('/api/v1/companies', $data);
    $response->assertStatus(200);
    $company_id = $response->json()['data']['id'];

    // Create Store
    $data = [];
    $data['active'] = 1;
    $data['store_no'] = faker()->randomNumber();
    $data['name'] = faker()->words(2, true);
    $data['city'] = 'toronto';
    $data['postal_code'] = 'L3T3A7';
    $data['province_code'] = 'ON';
    $data['country_code'] = 'CA';
    $data['coverage_radius'] = '50';
    $data['address'] = '200 avenue street';

    $response = $this->actingAs($user)->post('/api/v1/stores', $data);
    $response->assertStatus(200);
    $store_id = $response->json()['data']['id'];

    // Create Service
    $data = [];
    $data['active'] = 1;
    $data['name'] = faker()->words(2, true);

    $response = $this->actingAs($user)->post('/api/v1/services', $data);
    $response->assertStatus(200);
    $service_id = $response->json()['data']['id'];

    // Create workflow
    $data = [];
    $data['name'] = faker()->words(2, true);

    $response = $this->actingAs($user)->post('/api/v1/workflows', $data);
    $response->assertStatus(200);
    $workflow = $response->json()['data'];
    $workflow_id = $response->json()['data']['id'];

    // add workflownodes
    $data = [];
    $data['id'] = $workflow['id'];
    $data['name'] = $workflow['name'];
    $data['description'] = $workflow['description'];
    $data['active'] = $workflow['active'];
    $data['nodes'] = [];
    $data['nodes'][] = json_decode('{"id":"dndnode_0","type":"EditableNodeInput","position":{"x":410,"y":40},"data":{"label":"New"},"width":144,"height":30,"selected":false}', true);
    $data['nodes'][] = json_decode('{"id":"dndnode_1","type":"EditableNodeOutput","position":{"x":410,"y":100},"data":{"label":"Completed"},"width":144,"height":30,"selected":true,"positionAbsolute":{"x":410,"y":100},"dragging":false}', true);
    $data['edges'] = [];
    $data['edges'][] = json_decode('{"source":"dndnode_0","sourceHandle":null,"target":"dndnode_1","targetHandle":null,"id":"reactflow__edge-dndnode_0-dndnode_1"}', true);

    $response = $this->actingAs($user)->put('/api/v1/workflows/'.$workflow_id, $data);
    $response->assertStatus(200);
    $workflow = $response->json()['data'];

    $workflow_node_start = $workflow['nodes'][0];
    $workflow_node_end = $workflow['nodes'][1];

    $response = $this->actingAs($user)->get('/api/v1/actions');
    $actions = $response->json()['data'];
    $action = $actions[array_search('Book Appointment Action', array_column($actions, 'name'))];
    $action = $this->actingAs($user)->get('/api/v1/actions/'.$action['id'])->json();

    $data = [];
    $data['workflow_id'] = $workflow['id'];
    $data['label'] = $workflow_node_start['label'];
    $data['type'] = $workflow_node_start['type'];
    $data['position_x'] = $workflow_node_start['position_x'];
    $data['position_y'] = $workflow_node_start['position_y'];
    $data['actions'] = json_decode('[{"action_id":"2","permission_id":null,"status_to_id":"2","alternative_name":"Book Initial Appointment","variables":{"date_type":"Initial Appointment","can_add_appointment_on_spot":"false"}}]', true);
    $data['actions'][0]['action_id'] = $action['id'];
    $data['actions'][0]['status_to_id'] = $workflow_node_end['id'];

    $response = $this->actingAs($user)->put('/api/v1/workflow-nodes/'.$workflow_node_start['id'], $data);
    $response->assertStatus(200);
    $action_node = $response->json()['data']['actions'][0];

    // Create Service Availability
    $data = [];
    $data['company_id'] = $company_id;
    $data['service_id'] = $service_id;
    $data['workflow_id'] = $workflow_id;
    $data['store_id'] = [$store_id];

    $response = $this->actingAs($user)->post('/api/v1/service-availabilities', $data);
    $response->assertStatus(200);

    // Create Appointment
    $next_monday = new \DateTime('next monday');
    $next_friday = new \DateTime('next monday');
    $next_friday->modify('5 days');
    $data = [];
    $data['date_start'] = $next_monday->format('Y-m-d');
    $data['date_end'] = $next_friday->format('Y-m-d');
    $data['time_start'] = '01:00';
    $data['time_end'] = '09:00';
    $data['appointment_duration'] = '01:00';
    $data['appointment_padding'] = '00:30';
    $data['services'] = [$service_id];
    $data['stores'] = [$store_id];

    $this->refreshApplication();
    $response = $this->actingAs($user_sp)->post('/api/v1/appointments', $data);
    $response->assertStatus(200);
    $appointments = $response->json()['data'];

    // Create Lead
    $data = [];
    $data['service_id'] = $service_id;
    $data['first_name'] = faker()->firstName();
    $data['last_name'] = faker()->lastName();
    $data['email'] = faker()->email();
    $data['address'] = faker()->streetAddress();
    $data['city'] = faker()->city();
    $data['postal_code'] = 'L3T3C2';
    $data['province_code'] = 'ON';
    $data['country_code'] = 'CA';
    $data['phone1'] = faker()->phoneNumber();

    $this->refreshApplication();
    $response = $this->post('/api/v1/leads', $data);
    $response->assertStatus(200);
    $lead_id = $response->json()['data']['id'];

    // do action on the lead
    $response = $this->actingAs($user_sp)->get('/api/v1/leads');
    $response->assertStatus(200);
    $response->assertJsonPath('total', 1);
    $response->assertJsonPath('data.0.id', $lead_id);

    $response = $this->actingAs($user_sp)->get('/api/v1/leads/'.$lead_id);
    $response->assertStatus(200);
    $lead = $response->json();

    $response = $this->actingAs($user_sp)->get('/api/v1/leads/'.$lead_id.'/actions');
    $response->assertStatus(200);
    $response->assertJsonPath('0.action.name', 'Book Appointment Action');
    $action = $response->json()[0];

    $data = [];
    $data['appointment_id'] = $appointments[0]['id'];
    $response = $this->actingAs($user_sp)->put('/api/v1/leads/'.$lead_id.'/actions/'.$action['id'], $data);
    $response->assertStatus(200);

    $response = $this->actingAs($user_sp)->get('/api/v1/leads/'.$lead_id);
    $response->assertStatus(200);
    $response->assertJsonPath('status.label', 'Completed');
});
