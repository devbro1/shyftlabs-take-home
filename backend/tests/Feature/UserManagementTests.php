<?php

namespace Tests\Feature;

test('testCreateANewUser', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
