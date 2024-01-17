<?php

use App\Models\User;

test('phpinfo api exists', function () {
    $user = User::role('super-admin')->first();
    $response = $this->get('/api/v1/phpinfo')->assertStatus(401);
    $response = $this->actingAs($user)->get('/api/v1/phpinfo')->assertStatus(200);
    $response->assertStatus(200);
});

test('ping pong', function () {
    $this->get('/api/v1/ping')->assertStatus(200);
});
