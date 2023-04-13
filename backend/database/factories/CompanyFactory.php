<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => str()->random(10),
            'active' => 1,
            'address' => str()->random(10),
            'city' => str()->random(10),
            'province_code' => 'ON',
            'country_code' => 'CA',
            'postal_code' => 'M2J0B4',
            'email' => str()->random(10).'@devbro.com',
        ];
    }
}
