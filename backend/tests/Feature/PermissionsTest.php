<?php

use App\models\User;

use function Pest\Faker\faker;

test('testGetAllPermissions', function () {
    $user = User::role('super-admin')->first();
    $response = $this->actingAs($user)->get('/api/v1/permissions');
    $response->assertStatus(200);
});

test('testGetAPermissions', function () {
    $user = User::role('super-admin')->first();
    $response = $this->actingAs($user)->get('/api/v1/permissions/2');
    $response->assertStatus(200);
});

test('testCreateAndUpdatePermissions', function () {
    $user = User::role('super-admin')->first();
    $data = [];
    $data['name'] = faker()->words(3, true);
    $data['description'] = 'test';
    $response = $this->actingAs($user)->post('/api/v1/permissions/', $data);
    $response->assertStatus(200);

    $data['name'] = faker()->words(3, true);
    $data['description'] = 'test2';
    $response = $this->actingAs($user)->put('/api/v1/permissions/'.$response->json()['data']['id'], $data);
    $response->assertStatus(200);

    $response = $this->actingAs($user)->delete('/api/v1/permissions/'.$response->json()['data']['id']);
    $response->assertStatus(200);
});

test('testSystemPermissions', function () {
    $user = User::role('super-admin')->first();
    $data = [];
    $data['name'] = faker()->words(3, true);
    $data['description'] = 'test2';
    $response = $this->actingAs($user)->put('/api/v1/permissions/2', $data);
    $response->assertStatus(403);

    $response = $this->actingAs($user)->delete('/api/v1/permissions/2');
    $response->assertStatus(403);
});
