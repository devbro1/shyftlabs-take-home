<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $countries = [];
        $countries[] = ['alpha2Code' => 'CA', 'name' => 'Canada'];
        $countries[] = ['alpha2Code' => 'US', 'name' => 'United States of America'];
        $countries[] = ['alpha2Code' => 'MX', 'name' => 'Mexico'];

        foreach ($countries as $country) {
            DB::table('countries')->insert([
                'code' => $country['alpha2Code'],
                'name' => $country['name'],
            ]);
        }
    }
}
