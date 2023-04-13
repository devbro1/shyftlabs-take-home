<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $data_json =
            <<<'AAA'
[{"abbr":"AA","country":"US","name":"Armed Forces Americas"},
 {"abbr":"AE","country":"US","name":"Armed Forces Other"},
 {"abbr":"AL","country":"US","name":"Alabama"},
 {"abbr":"AK","country":"US","name":"Alaska"},
 {"abbr":"AP","country":"US","name":"Armed Forces Pacific"},
 {"abbr":"AS","country":"US","name":"American Samoa"},
 {"abbr":"AZ","country":"US","name":"Arizona"},
 {"abbr":"AR","country":"US","name":"Arkansas"},
 {"abbr":"CA","country":"US","name":"California"},
 {"abbr":"CO","country":"US","name":"Colorado"},
 {"abbr":"CT","country":"US","name":"Connecticut"},
 {"abbr":"DE","country":"US","name":"Delaware"},
 {"abbr":"DC","country":"US","name":"District of Columbia"},
 {"abbr":"FL","country":"US","name":"Florida"},
 {"abbr":"FM","country":"US","name":"Federated States of Micronesia"},
 {"abbr":"GA","country":"US","name":"Georgia"},
 {"abbr":"GU","country":"US","name":"Guam"},
 {"abbr":"HI","country":"US","name":"Hawaii"},
 {"abbr":"ID","country":"US","name":"Idaho"},
 {"abbr":"IL","country":"US","name":"Illinois"},
 {"abbr":"IN","country":"US","name":"Indiana"},
 {"abbr":"IA","country":"US","name":"Iowa"},
 {"abbr":"KS","country":"US","name":"Kansas"},
 {"abbr":"KY","country":"US","name":"Kentucky"},
 {"abbr":"LA","country":"US","name":"Louisiana"},
 {"abbr":"ME","country":"US","name":"Maine"},
 {"abbr":"MD","country":"US","name":"Maryland"},
 {"abbr":"MA","country":"US","name":"Massachusetts"},
 {"abbr":"MH","country":"US","name":"Marshall Islands"},
 {"abbr":"MI","country":"US","name":"Michigan"},
 {"abbr":"MN","country":"US","name":"Minnesota"},
 {"abbr":"MS","country":"US","name":"Mississippi"},
 {"abbr":"MO","country":"US","name":"Missouri"},
 {"abbr":"MP","country":"US","name":"Northern Mariana Islands"},
 {"abbr":"MT","country":"US","name":"Montana"},
 {"abbr":"NE","country":"US","name":"Nebraska"},
 {"abbr":"NV","country":"US","name":"Nevada"},
 {"abbr":"NH","country":"US","name":"New Hampshire"},
 {"abbr":"NJ","country":"US","name":"New Jersey"},
 {"abbr":"NM","country":"US","name":"New Mexico"},
 {"abbr":"NY","country":"US","name":"New York"},
 {"abbr":"NC","country":"US","name":"North Carolina"},
 {"abbr":"ND","country":"US","name":"North Dakota"},
 {"abbr":"OH","country":"US","name":"Ohio"},
 {"abbr":"OK","country":"US","name":"Oklahoma"},
 {"abbr":"OR","country":"US","name":"Oregon"},
 {"abbr":"PA","country":"US","name":"Pennsylvania"},
 {"abbr":"PR","country":"US","name":"Puerto Rico"},
 {"abbr":"PW","country":"US","name":"Palau"},
 {"abbr":"RI","country":"US","name":"Rhode Island"},
 {"abbr":"SC","country":"US","name":"South Carolina"},
 {"abbr":"SD","country":"US","name":"South Dakota"},
 {"abbr":"TN","country":"US","name":"Tennessee"},
 {"abbr":"TX","country":"US","name":"Texas"},
 {"abbr":"UT","country":"US","name":"Utah"},
 {"abbr":"VT","country":"US","name":"Vermont"},
 {"abbr":"VA","country":"US","name":"Virginia"},
 {"abbr":"VI","country":"US","name":"Virgin Islands"},
 {"abbr":"WA","country":"US","name":"Washington"},
 {"abbr":"WV","country":"US","name":"West Virginia"},
 {"abbr":"WI","country":"US","name":"Wisconsin"},
 {"abbr":"WY","country":"US","name":"Wyoming"},
 {"abbr":"AB","country":"CA","name":"Alberta"},
 {"abbr":"BC","country":"CA","name":"British Columbia"},
 {"abbr":"MB","country":"CA","name":"Manitoba"},
 {"abbr":"NB","country":"CA","name":"New Brunswick"},
 {"abbr":"NL","country":"CA","name":"Newfoundland"},
 {"abbr":"NT","country":"CA","name":"Northwest Territories"},
 {"abbr":"NS","country":"CA","name":"Nova Scotia"},
 {"abbr":"NU","country":"CA","name":"Nunavut"},
 {"abbr":"ON","country":"CA","name":"Ontario"},
 {"abbr":"PE","country":"CA","name":"Prince Edward Island"},
 {"abbr":"QC","country":"CA","name":"Quebec"},
 {"abbr":"SK","country":"CA","name":"Saskatchewan"},
 {"abbr":"YT","country":"CA","name":"Yukon"}]
AAA;

        $data = json_decode($data_json, true);

        foreach ($data as $row) {
            DB::table('provinces')->insert(
                [
                    'code' => $row['abbr'],
                    'abbreviation' => $row['abbr'],
                    'name' => $row['name'],
                    'country_code' => $row['country'],
                ]
            );
        }
    }
}
