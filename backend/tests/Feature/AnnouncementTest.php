<?php

namespace Tests\Feature;

use App\Models\User;

test('testGetAnnouncementList', function () {
    $response = $this->get('/api/v1/announcements');
    $response->assertStatus(401);
    $user = User::first();
    $response = $this->actingAs($user)->get('/api/v1/announcements');

    $response->assertStatus(200);
});

test('testCreateUpdateShowDeleteAnnouncement', function () {
    $user = User::permission('create announcement')->first();

    $response = $this->actingAs($user)->post('/api/v1/announcements', ['title' => 'not enough']);
    $response->assertStatus(422);

    $response = $this->actingAs($user)->post('/api/v1/announcements', ['body' => 'not enough']);
    $response->assertStatus(422);

    $data = [];
    $data['title'] = 'test';
    $data['body'] = 'test';
    $response = $this->actingAs($user)->post('/api/v1/announcements', $data);
    $response->assertStatus(200);
    $id = $response->json()['data']['id'];

    $data['title'] = 'test2';
    $data['body'] = 'test2';
    $response = $this->actingAs($user)->put('/api/v1/announcements/'.$id, $data);
    $response->assertStatus(200);

    $response = $this->actingAs($user)->get('/api/v1/announcements/'.$id);
    $response->assertStatus(200);

    $response = $this->actingAs($user)->get('/api/v1/announcements/'.($id + 100));
    $response->assertStatus(404);

    $response = $this->actingAs($user)->delete('/api/v1/announcements/'.$id);
    $response->assertStatus(200);
});
