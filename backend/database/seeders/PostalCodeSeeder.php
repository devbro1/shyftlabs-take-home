<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;

class PostalCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $data = [];
        $file_name = 'ZipCodeFiles.zip';
        $delimiter = ',';
        $header = null;

        if (App::environment('local')) {
            $file_name = 'ZipCodeFiles_local.zip';
        }

        $za = new \ZipArchive();
        $za->open(storage_path('app/private/'.$file_name));
        $za->extractTo(storage_path('app/private/postal_data'));

        DB::table('postal_codes')->truncate();

        $canada_file = storage_path('app/private/postal_data').'/CanadianPostalCodes202112.csv';
        if (($handle = fopen($canada_file, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header) {
                    $header = $row;
                } elseif ('local' !== env('APP_ENV', 'local') || 'ON' === $row[2]) {
                    $data2 = array_combine(['code', 'city', 'province_code', 'time_zone_id', 'latitude', 'longitude'], $row);
                    $data2['country_code'] = 'CA';
                    $data2['code'] = str_replace(' ', '', $data2['code']);
                    $data[$data2['code']] = $data2;
                }

                if (count($data) > 1000) {
                    DB::table('postal_codes')->insertOrIgnore($data);
                    $data = [];
                }
            }
            fclose($handle);
        }

        if (count($data) > 0) {
            DB::table('postal_codes')->insertOrIgnore($data);
            $data = [];
        }

        $header = null;
        $usa_file = storage_path('app/private/postal_data').'/USZIPCodes202112.csv';
        if (($handle = fopen($usa_file, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header) {
                    $header = $row;
                } else {
                    $data2 = array_combine(['code', 'city', 'county', 'province_code', 'CountyFIPS', 'StateFIPS', 'time_zone_id', 'DayLightSavings', 'latitude', 'longitude'], $row);
                    $data[sprintf('%05d', $data2['code'])] = [
                        'code' => sprintf('%05d', $data2['code']),
                        'city' => $data2['city'],
                        'province_code' => $data2['province_code'],
                        'country_code' => 'US',
                        'time_zone_id' => (int) $data2['time_zone_id'],
                        'latitude' => (float) $data2['latitude'],
                        'longitude' => (float) $data2['longitude'],
                    ];
                }

                if (count($data) > 1000) {
                    DB::table('postal_codes')->insertOrIgnore($data);
                    $data = [];
                }
            }
            fclose($handle);

            if (count($data) > 0) {
                DB::table('postal_codes')->insertOrIgnore($data);
                $data = [];
            }
        }
    }
}
