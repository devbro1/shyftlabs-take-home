<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('services')->insert([
            'name' => 'Rental',
        ]);

        DB::table('services')->insert([
            'name' => 'Used Cars',
        ]);

        DB::table('services')->insert([
            'name' => 'New Cars',
        ]);

        DB::table('services')->insert([
            'name' => 'Repairs',
        ]);
    }
}
