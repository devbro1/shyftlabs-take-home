<?php

use App\models\User;

test('you cannot become someone else without login', function () {
    $response = $this->get('/api/v1/tokens/impersonate/1');
    $response->assertStatus(403);
});

test('user can impersonate someone else', function () {
    $user = User::role('super-admin')->first();
    $response = $this->actingAs($user)->get('/api/v1/tokens/impersonate/1');
    $response->assertStatus(200);
});
