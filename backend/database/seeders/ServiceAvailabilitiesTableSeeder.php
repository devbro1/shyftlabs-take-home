<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ServiceAvailabilitiesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        $store_ids = \DB::table('stores')->pluck('id')->toArray();
        $service_ids = \DB::table('services')->pluck('id')->toArray();
        $company_ids = \DB::table('companies')->pluck('id')->toArray();

        \DB::table('service_availabilities')->delete();

        $data = [];
        for ($i = 0; $i < 2; ++$i) {
            $data[] =
                [
                    'store_id' => $store_ids[array_rand($store_ids)],
                    'company_id' => $company_ids[array_rand($company_ids)],
                    'service_id' => $service_ids[array_rand($service_ids)],
                    'workflow_id' => 1,
                ];
        }

        \DB::table('service_availabilities')->insertOrIgnore($data);
    }
}
