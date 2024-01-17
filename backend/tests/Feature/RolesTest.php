<?php

namespace Tests\Feature;

use App\models\User;

use function Pest\Faker\fake;

test('testGetAllRoles', function () {
    $user = User::role('super-admin')->first();
    $response = $this->actingAs($user)->get('/api/v1/roles');
    $response->assertStatus(200);
});

test('testCreateGetUpdateDeleteRole', function () {
    $user = User::role('super-admin')->first();
    $data = [];
    $data['name'] = fake()->words(3, true);
    $data['description'] = 'test';
    $data['permissions'] = [];
    $response = $this->actingAs($user)->post('/api/v1/roles/', $data);
    $response->assertStatus(200);

    $response = $this->actingAs($user)->get('/api/v1/roles/'.$response->json()['data']['id']);
    $response->assertStatus(200);

    $data['name'] = fake()->words(3, true);
    $data['description'] = 'test2';
    $response = $this->actingAs($user)->put('/api/v1/roles/'.$response->json()['id'], $data);
    $response->assertStatus(200);

    $response = $this->actingAs($user)->delete('/api/v1/roles/'.$response->json()['data']['id']);
    $response->assertStatus(200);
});
