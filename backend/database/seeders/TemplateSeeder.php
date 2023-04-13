<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('templates')->insert([
            'name' => 'new_user_email',
            'description' => 'email sent for new users',
            'body' => '',
            'active' => 1,
        ]);

        DB::table('templates')->insert([
            'name' => 'password_reset_email',
            'description' => 'password reset email',
            'body' => '',
            'active' => 1,
        ]);
    }
}
