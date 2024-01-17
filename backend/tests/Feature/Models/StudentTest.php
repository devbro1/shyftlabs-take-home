<?php

use App\Models\User;

test('get student list', function () {
    $user = User::role('super-admin')->first();
    $this->actingAs($user);

    $response = $this->get('/api/v1/students');
    $response->assertStatus(200);
});

test('full student flow create,update,delete', function () {
    $user = User::role('super-admin')->first();
    $this->actingAs($user);

    $data = [
        'first_name' => fake()->firstName(),
        'family_name' => fake()->lastName(),
        'email' => fake()->email(),
        'date_of_birth' => '2010-1-2',
    ];
    $response = $this->post('/api/v1/students',$data);
    $response->assertStatus(200);
    $student_id = $response->json()['data']['id'];

    $response = $this->get('/api/v1/students/' . $student_id);
    $response->assertStatus(200);


    $data = [
        'first_name' => fake()->firstName(),
        'family_name' => fake()->lastName(),
        'email' => fake()->email(),
        'date_of_birth' => '2009-1-2',
    ];
    $response = $this->get('/api/v1/students/' . $student_id, $data);
    $response->assertStatus(200);

    $response = $this->get('/api/v1/students/' . $student_id);
    $response->assertStatus(200);

    $response = $this->delete('/api/v1/students/' . $student_id);
    $response->assertStatus(200);

    $response = $this->get('/api/v1/students/' . $student_id);
    $response->assertStatus(404);

});


test('student field validation', function () {
    $user = User::role('super-admin')->first();
    $this->actingAs($user);

    $response = $this->post('/api/v1/students',[
        'family_name' => fake()->lastName(),
        'email' => fake()->email(),
        'date_of_birth' => '2010-1-2',
    ]);
    $response->assertStatus(422);

    $response = $this->post('/api/v1/students',[
        'first_name' => fake()->firstName(),
        'email' => fake()->email(),
        'date_of_birth' => '2010-1-2',
    ]);
    $response->assertStatus(422);

    $response = $this->post('/api/v1/students',[
        'first_name' => fake()->firstName(),
        'family_name' => fake()->lastName(),
        'date_of_birth' => '2010-1-2',
    ]);
    $response->assertStatus(422);

    $response = $this->post('/api/v1/students',[
        'first_name' => fake()->firstName(),
        'family_name' => fake()->lastName(),
        'email' => fake()->email(),
    ]);
    $response->assertStatus(422);


    $response = $this->post('/api/v1/students',[
        'first_name' => 'A',
        'family_name' => fake()->lastName(),
        'email' => fake()->email(),
        'date_of_birth' => '2010-1-2',
    ]);
    $response->assertStatus(422);


    $response = $this->post('/api/v1/students',[
        'first_name' => fake()->firstName(),
        'family_name' => 'A',
        'email' => fake()->email(),
        'date_of_birth' => '2010-1-2',
    ]);
    $response->assertStatus(422);

    $response = $this->post('/api/v1/students',[
        'first_name' => fake()->firstName(),
        'family_name' => fake()->lastName(),
        'email' => "QWEQWE",
        'date_of_birth' => '2010-1-2',
    ]);
    $response->assertStatus(422);

    $response = $this->post('/api/v1/students',[
        'first_name' => fake()->firstName(),
        'family_name' => fake()->lastName(),
        'email' => fake()->email(),
        'date_of_birth' => '2024-1-1',
    ]);
    $response->assertStatus(422);
});