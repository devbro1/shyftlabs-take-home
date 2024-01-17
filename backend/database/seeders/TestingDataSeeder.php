<?php

namespace Database\Seeders;

use App\Models\Drug;
use Illuminate\Database\Seeder;

class TestingDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->call(StudentSeeder::class);
    }
}
