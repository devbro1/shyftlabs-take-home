<?php

test('testGetCountryList', function () {
    $response = $this->get('/api/v1/countries');
    $response->assertStatus(200);
});

test('testGetCountryProvinces', function () {
    $response = $this->get('/api/v1/countries/CA');
    $response->assertStatus(200);
    $response->assertJsonStructure(['code', 'name', 'provinces' => [['code', 'abbreviation', 'name', 'country_code']]]);
});
