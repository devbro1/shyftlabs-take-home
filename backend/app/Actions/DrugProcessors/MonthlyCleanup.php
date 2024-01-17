<?php

namespace App\Actions\DrugProcessors;

use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\DB;

class MonthlyCleanup
{
    use AsAction;

    public function handle($params)
    {
        $rc = [];
        $q = DB::table('drugs')->update(
            [
                'action' => '',
                'slf_action' => '',
                'clic_action' => '',
                'gwl_action' => '',
                'gwl_cada_action' => '',
                'cs_action' => '',
                'cs_iw_action' => '',
                'jg_action' => '',
                'modified' => 'N',
            ]
        );

        return $rc;
    }
}
