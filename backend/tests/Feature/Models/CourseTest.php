<?php

use App\Models\User;

test('get course list', function () {
    $user = User::role('super-admin')->first();
    $this->actingAs($user);

    $response = $this->get('/api/v1/courses');
    $response->assertStatus(200);
});

test('full course flow create,update,delete', function () {
    $user = User::role('super-admin')->first();
    $this->actingAs($user);

    $data = [
        'name' => fake()->words(2,true),
    ];
    $response = $this->post('/api/v1/courses',$data);
    $response->assertStatus(200);
    $course_id = $response->json()['data']['id'];

    $response = $this->get('/api/v1/courses/' . $course_id);
    $response->assertStatus(200);


    $data = [
        'name' => fake()->words(2,true),
    ];
    $response = $this->get('/api/v1/courses/' . $course_id, $data);
    $response->assertStatus(200);

    $response = $this->get('/api/v1/courses/' . $course_id);
    $response->assertStatus(200);

    $response = $this->delete('/api/v1/courses/' . $course_id);
    $response->assertStatus(200);

    $response = $this->get('/api/v1/courses/' . $course_id);
    $response->assertStatus(404);

});


test('course field validation', function () {
    $user = User::role('super-admin')->first();
    $this->actingAs($user);

    $response = $this->post('/api/v1/courses',[
        'name' => '',
    ]);
    $response->assertStatus(422);
});