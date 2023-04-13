<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Permission;
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
            'password' => bcrypt('password'),
            'active' => 1,
        ]);

        $user = USER::where('username', 'farzad')->first();
        $user->assignRole('super-admin');

        User::factory()->count(20)->create();

        $perm = Permission::where(['name' => 'Service Lead'])->first();
        $users = User::factory()->count(3)->create();
        foreach ($users as $user) {
            $user->givePermissionTo($perm);
        }
    }
}
