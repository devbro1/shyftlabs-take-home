<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoreGroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $groups = [];
        $groups[] = ['parent_id' => null, 'name' => 'SAD', 'description' => 'Super Administrator'];
        $groups[] = ['parent_id' => null, 'name' => 'AD', 'description' => 'Administrator'];
        $groups[] = ['parent_id' => null, 'name' => 'RESEARCHER', 'description' => 'Researcher'];
        $groups[] = ['parent_id' => null, 'name' => 'PARTICIPANT', 'description' => 'Participant'];
        $groups[] = ['parent_id' => null, 'name' => 'NURSE', 'description' => 'Nurse'];
        $groups[] = ['parent_id' => null, 'name' => 'UNREGISTERED', 'description' => 'Unregistered User'];

        foreach ($groups as $group) {
            DB::table('core_groups')->insert($group);
        }
    }
}
