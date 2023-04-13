<?php

    test('testGetPostalCodeCa', function () {
        $response = $this->get('/api/v1/postal-codes/M2J0B4');
        $response->assertStatus(200);
    });

    test('testGetPostalCodeUs', function () {
        $response = $this->get('/api/v1/postal-codes/90005');
        $response->assertStatus(200);
    });

    test('testBadPostalCode', function () {
        $response = $this->get('/api/v1/postal-codes/MAA');
        $response->assertStatus(404);
    });

    test('testGetCountryList', function () {
        $response = $this->get('/api/v1/countries');
        $response->assertStatus(200);
    });

    test('testGetCountryProvinces', function () {
        $response = $this->get('/api/v1/countries/CA');
        $response->assertStatus(200);
        $response->assertJsonStructure(['code', 'name', 'provinces' => [['code', 'abbreviation', 'name', 'country_code']]]);
    });
