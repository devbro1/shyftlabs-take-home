<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('users')->insert([
            'username' => 'farzad',
            'email' => 'farzadk@gmail.com',
            'email_verified_at' => now(),
            'full_name' => 'Farzad K',
            'password' => bcrypt('Rass(123)'),
            'active' => 1,
        ]);

        $user = USER::where('username', 'farzad')->first();
        $user->assignRole('super-admin');
    }
}
