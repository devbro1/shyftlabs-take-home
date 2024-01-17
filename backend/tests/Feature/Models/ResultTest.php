<?php

use App\Models\User;
use App\Models\Student;
use App\Models\Course;

test('get results list', function () {
    $user = User::role('super-admin')->first();
    $this->actingAs($user);

    $response = $this->get('/api/v1/results');
    $response->assertStatus(200);
});

test('get specific list', function () {
    $user = User::role('super-admin')->first();
    $this->actingAs($user);

    $response = $this->get('/api/v1/results/5');
    $response->assertStatus(200);
});

test('full result flow create', function () {
    $student = Student::all()->random();
    $course = Course::all()->random();

    $user = User::role('super-admin')->first();
    $this->actingAs($user);

    $response = $this->post('/api/v1/results',[
        'score' => 'A',
        'course_id' => $course->id,
        'student_id' => $student->id,
    ]);
    $response->assertStatus(200);
    $result_id = $response->json()['data']['id'];

    $response = $this->get('/api/v1/results/' . $result_id);
    $response->assertStatus(200);
});


test('result field validation', function () {
    $user = User::role('super-admin')->first();
    $this->actingAs($user);

    $student = Student::all()->random();
    $course = Course::all()->random();

    $response = $this->post('/api/v1/results',[
        'course_id' => $course->id,
        'student_id' => $student->id,
    ]);
    $response->assertStatus(422);

    $response = $this->post('/api/v1/results',[
        'score' => 'A',
        'student_id' => $student->id,
    ]);
    $response->assertStatus(422);

    $response = $this->post('/api/v1/results',[
        'score' => 'A',
        'course_id' => $course->id,
    ]);
    $response->assertStatus(422);

    $response = $this->post('/api/v1/results',[
        'score' => 'G',
        'course_id' => $course->id,
        'student_id' => $student->id,
    ]);
    $response->assertStatus(422);


    $response = $this->post('/api/v1/results',[
        'score' => '',
        'course_id' => $course->id,
        'student_id' => $student->id,
    ]);
    $response->assertStatus(422);


    $response = $this->post('/api/v1/results',[
        'score' => 'A',
        'course_id' => 100000000,
        'student_id' => $student->id,
    ]);
    $response->assertStatus(422);

    $response = $this->post('/api/v1/results',[
        'score' => 'A',
        'course_id' => $course->id,
        'student_id' => 100000000,
    ]);
    $response->assertStatus(422);

    
});