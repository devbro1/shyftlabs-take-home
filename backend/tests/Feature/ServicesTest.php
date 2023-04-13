<?php

use App\models\User;

use function Pest\Faker\faker;

// A basic feature test example.
test('testGetAllServices', function () {
    $user = User::role('super-admin')->first();
    $response = $this->actingAs($user)->get('/api/v1/services');
    $response->assertStatus(200);
});

test('testCreateGetUpdateDeleteService', function () {
    $user = User::role('super-admin')->first();
    $data = [];
    $data['name'] = faker()->words(3, true);
    $data['active'] = true;
    $response = $this->actingAs($user)->post('/api/v1/services/', $data);
    $response->assertStatus(200);
    $id = $response->json()['data']['id'];

    $response = $this->actingAs($user)->get('/api/v1/services/'.$id);
    $response->assertStatus(200);

    $data['name'] = faker()->words(3, true);
    $response = $this->actingAs($user)->put('/api/v1/services/'.$id, $data);
    $response->assertStatus(200);

    $response = $this->actingAs($user)->delete('/api/v1/services/'.$id);
    $response->assertStatus(403);
});
