<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoresTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        DB::table('stores')->delete();

        DB::table('stores')->insert([
            0 => [
                'created_at' => '2022-01-17 00:57:37',
                'updated_at' => '2022-01-17 00:59:06',
                'active' => true,
                'store_no' => '79553',
                'name' => 'distinctio qui',
                'address' => '878 Stroman Vista',
                'city' => 'Goldaport',
                'province_code' => 'ON',
                'country_code' => 'CA',
                'postal_code' => 'L3T1E1',
                'longitude' => null,
                'latitude' => null,
            ],
            1 => [
                'created_at' => '2022-01-17 00:57:55',
                'updated_at' => '2022-01-17 00:59:10',
                'active' => true,
                'store_no' => '65233',
                'name' => 'aut voluptas',
                'address' => '195 Stanford Lodge',
                'city' => 'Port Coltenville',
                'province_code' => 'ON',
                'country_code' => 'CA',
                'postal_code' => 'M2J1B1',
                'longitude' => null,
                'latitude' => null,
            ],
        ]);
    }
}
