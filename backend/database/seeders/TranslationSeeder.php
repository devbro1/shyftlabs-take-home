<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        foreach ($rows as $row) {
            if (!Translation::where('key', $row['key'])->where('language', $row['language'])->where('namespace', $row['namespace'])->exists()) {
                Translation::insert([
                    'key' => $row['key'],
                    'namespace' => $row['namespace'],
                    'language' => $row['language'],
                    'translation' => $row['translation'],
                ]);
            }
        }
    }
}
