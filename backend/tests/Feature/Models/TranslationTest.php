<?php

test('get all translations', function () {
    $response = $this->get('/api/v1/cached-translations/en');
    $response->assertStatus(200);
});
