<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class WorkflowsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        \DB::table('workflows')->delete();

        \DB::table('workflows')->insert([
            0 => [
                'id' => 1,
                'created_at' => '2022-10-11 03:24:02',
                'updated_at' => '2022-10-11 03:24:02',
                'name' => 'Auto Sale Workflow',
                'description' => 'To sell cars efficiently',
                'active' => true,
            ],
            1 => [
                'id' => 2,
                'created_at' => '2022-12-11 23:43:17',
                'updated_at' => '2022-12-11 23:43:17',
                'name' => 'Costco USA workflow',
                'description' => 'template for costco usa',
                'active' => true,
            ],
        ]);
    }
}
