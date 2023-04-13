<?php

namespace Tests\Feature;

use App\Models\User;

use function Pest\Faker\faker;

test('testGetAllLeads', function () {
    $response = $this->get('/api/v1/leads');
    $response->assertStatus(401);

    $user = User::role('super-admin')->first();
    $response = $this->actingAs($user)->get('/api/v1/leads');
    $response->assertStatus(200);
});

test('testCreateLeadAsCustomer', function () {
    $sa = \App\Models\ServiceAvailability::first();
    $data = [];
    $data['service_id'] = $sa->service_id;
    $data['first_name'] = faker()->firstName;
    $data['last_name'] = faker()->lastName;
    $data['email'] = faker()->email;
    $data['postal_code'] = 'M2J0B4';
    $data['province_code'] = 'ON';
    $data['country_code'] = 'CA';
    $data['phone1'] = faker()->numerify('(###) ###-####');
    $data['address'] = faker()->streetAddress;
    $data['city'] = faker()->city;
    $response = $this->post('/api/v1/leads', $data);
    $response->assertStatus(200);

    // $user = User::permission('Service Lead')->get()->random();
    // $response = $this->actingAs($user)->get('/api/v1/leads',$data);
    // $response->assertStatus(200);
});

test('testCreateLeadAsSP', function () {
    $sa = \App\Models\ServiceAvailability::first();
    $data = [];
    $data['service_id'] = $sa->service_id;
    $data['first_name'] = faker()->firstName;
    $data['last_name'] = faker()->lastName;
    $data['email'] = faker()->email;
    $data['postal_code'] = 'M2J0B4';
    $data['province_code'] = 'ON';
    $data['country_code'] = 'CA';
    $data['phone1'] = faker()->numerify('(###) ###-####');
    $data['address'] = faker()->streetAddress;
    $data['city'] = faker()->city;
    $response = $this->actingAs($sa->company->owners->first())->post('/api/v1/leads', $data);
    $response->assertStatus(200);

    $response = $this->actingAs($sa->company->owners->first())->get('/api/v1/leads/'.$response->json()['data']['id']);
    $response->assertStatus(200);

    // $user = User::permission('Service Lead')->get()->random();
    // $response = $this->actingAs($user)->get('/api/v1/leads',$data);
    // $response->assertStatus(200);
});

test('testCreateLeadAsSPWithStore', function () {
    $sa = \App\Models\ServiceAvailability::first();
    $data = [];
    $data['service_id'] = $sa->service_id;
    $data['first_name'] = faker()->firstName;
    $data['last_name'] = faker()->lastName;
    $data['email'] = faker()->email;
    $data['postal_code'] = 'M2J0B4';
    $data['province_code'] = 'ON';
    $data['country_code'] = 'CA';
    $data['phone1'] = faker()->numerify('(###) ###-####');
    $data['address'] = faker()->streetAddress;
    $data['city'] = faker()->city;
    $data['store_id'] = $sa->store_id;
    $response = $this->actingAs($sa->company->owners->first())->post('/api/v1/leads', $data);
    $response->assertStatus(200);

    // $user = User::permission('Service Lead')->get()->random();
    // $response = $this->actingAs($user)->get('/api/v1/leads',$data);
    // $response->assertStatus(200);
});
