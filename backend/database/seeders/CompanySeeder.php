<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        if (0 == User::permission('Service Lead')->count()) {
            $user = User::factory()->create();
            $user->givePermissionTo('Service Lead');
            $user->save();
        }

        Company::factory()->count(5)->create()
            ->each(function ($company) {
                $company->owners()->save(User::permission('Service Lead')->get()->random(), ['position' => 'owner']);
            })
        ;
    }
}
