<?php

namespace App\Actions\Exports;

use App\Models\Drug;
use App\Models\Export;
use Lorisleiva\Actions\Concerns\AsAction;

class ExportDrugFinderUpdate extends ExportDrugFinderNew
{
    use AsAction;

    public function handle(Export $export)
    {
        $this->file_name = 'drugfinder update Export ('.date('Ymd').')';
        $drugs = Drug::where('updated_at', '>=', \Carbon\Carbon::now()->subDays(30));
        $this->processRequest($export, $drugs);
    }
}
