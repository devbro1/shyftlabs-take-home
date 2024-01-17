<?php

namespace App\Actions\Exports;

use App\Models\File;
use App\Models\Export;
use Lorisleiva\Actions\Concerns\AsAction;
use League\Csv\Writer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\NOCMain;
use App\Models\NOCTemp;
use Illuminate\Support\Facades\DB;

class ExportNOCNAs extends ExportBase
{
    use AsAction;

    public function handle(Export $export)
    {
        $path = 'public/export/'.Str::kebab($export->name.'-'.$export->created_at).'.csv';
        $full_path = Storage::path($path);
        $writer = Writer::createFromPath($full_path, 'w+');
        $first = true;

        $attributes = array_keys(NOCMain::first()->getAttributes());
        $q = DB::table('noc_temps as t')->select(['t.id as temp_id', 'm.id as main_id', 't.noc_dp_din_product_id'])
            ->leftJoin('noc_mains as m', 'm.noc_dp_din_product_id', '=', 't.noc_dp_din_product_id')
            ->whereNull('m.id')
        ;

        foreach ($attributes as $attr) {
            if (in_array($attr, ['id', 'created_at', 'updated_at', 'file_date'])) {
                continue;
            }
            $q = $q->orWhereColumn('m.'.$attr, '!=', 't.'.$attr);
        }

        foreach ($q->get() as $row) {
            $m = NOCMain::find($row->main_id);
            $t = NOCTemp::find($row->temp_id);

            if ('N/A' !== $t->noc_dp_din) {
                continue;
            }

            $data = $t->getAttributes();
            if ($first) {
                $writer->insertOne(array_keys($data));
                $first = false;
            }
            $writer->insertOne($data);
        }

        $file = File::saveToDB($path);

        $export->file_id = $file->id;
        $export->save();
    }
}
