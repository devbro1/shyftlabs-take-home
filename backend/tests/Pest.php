<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;

use function Pest\Faker\faker;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(Tests\TestCase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $test->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function generateRandomBase($test)
{
    $rc = [];
    $user = User::role('super-admin')->first();

    // Create Users
    $data = [];
    $data['username'] = faker()->userName();
    $data['full_name'] = faker()->name();
    $data['email'] = faker()->email();
    $data['permissions'] = ['Service Lead'];

    $response = $test->actingAs($user)->post('/api/v1/users', $data);
    $response->assertStatus(200);
    $sp_id = $response->json()['data']['id'];
    $user_sp = User::find($sp_id);
    $rc['user_sp'] = $user_sp;
    $rc['sp_id'] = $sp_id;

    $data = [];
    $data['username'] = faker()->userName();
    $data['full_name'] = faker()->name();
    $data['email'] = faker()->email();
    $data['permissions'] = ['Service Lead'];

    $response = $test->actingAs($user)->post('/api/v1/users', $data);
    $response->assertStatus(200);
    $emp_id = $response->json()['data']['id'];
    $rc['emp_id'] = $emp_id;

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

    $response = $test->actingAs($user)->post('/api/v1/companies', $data);
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

    $response = $test->actingAs($user)->post('/api/v1/stores', $data);
    $response->assertStatus(200);
    $store_id = $response->json()['data']['id'];

    // Create Service
    $data = [];
    $data['active'] = 1;
    $data['name'] = faker()->words(2, true);

    $response = $test->actingAs($user)->post('/api/v1/services', $data);
    $response->assertStatus(200);
    $service_id = $response->json()['data']['id'];
    $rc['service_id'] = $service_id;

    // Create workflow
    $data = [];
    $data['name'] = faker()->words(2, true);

    $response = $test->actingAs($user)->post('/api/v1/workflows', $data);
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

    $response = $test->actingAs($user)->put('/api/v1/workflows/'.$workflow_id, $data);
    $response->assertStatus(200);
    $workflow = $response->json()['data'];
    $rc['workflow'] = $workflow;

    $workflow_node_start = $workflow['nodes'][0];
    $workflow_node_end = $workflow['nodes'][1];

    $response = $test->actingAs($user)->get('/api/v1/actions');
    $actions = $response->json()['data'];
    $action = $actions[array_search('Book Appointment Action', array_column($actions, 'name'))];
    $action = $test->actingAs($user)->get('/api/v1/actions/'.$action['id'])->json();

    $data = [];
    $data['workflow_id'] = $workflow['id'];
    $data['label'] = $workflow_node_start['label'];
    $data['type'] = $workflow_node_start['type'];
    $data['position_x'] = $workflow_node_start['position_x'];
    $data['position_y'] = $workflow_node_start['position_y'];
    $data['actions'] = json_decode('[{"action_id":"2","permission_id":null,"status_to_id":"2","alternative_name":"Book Initial Appointment","variables":{"date_type":"Initial Appointment","can_add_appointment_on_spot":"false"}}]', true);
    $data['actions'][0]['action_id'] = $action['id'];
    $data['actions'][0]['status_to_id'] = $workflow_node_end['id'];
    $data['actions'][0]['permission_id'] = null;
    $data['actions'][0]['alternative_name'] = 'Book Initial Appointment';
    $data['actions'][0]['variables'] = ['duration' => 0];

    // $response = $test->actingAs($user)->put('/api/v1/workflow-nodes/'.$workflow_node_start['id'], $data);
    // $response->assertStatus(200);
    // $action_node = $response->json()['data']['actions'][0];

    // Create Service Availability
    $data = [];
    $data['company_id'] = $company_id;
    $data['service_id'] = $service_id;
    $data['workflow_id'] = $workflow_id;
    $data['store_id'] = [$store_id];

    $response = $test->actingAs($user)->post('/api/v1/service-availabilities', $data);
    $response->assertStatus(200);

    return $rc;
}

function generateRandomLead($test, $rc)
{
    // Create Lead
    $data = [];
    $data['service_id'] = $rc['service_id'];
    $data['first_name'] = faker()->firstName();
    $data['last_name'] = faker()->lastName();
    $data['email'] = faker()->email();
    $data['address'] = faker()->streetAddress();
    $data['city'] = faker()->city();
    $data['postal_code'] = 'L3T3C2';
    $data['province_code'] = 'ON';
    $data['country_code'] = 'CA';
    $data['phone1'] = faker()->phoneNumber();

    Auth::logout();
    $response = $test->post('/api/v1/leads', $data);
    $response->assertStatus(200);
    $lead_id = $response->json()['data']['id'];
    $rc['lead_id'] = $lead_id;

    return $rc;
}
