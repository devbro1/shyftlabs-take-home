<?php

namespace App\Actions\Exports;

use App\Models\Drug;
use App\Models\File;
use App\Models\Export;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportDinMapping extends ExportBase
{
    use AsAction;

    public function handle(Export $export)
    {
        $filename = Str::kebab($export->name);
        $extension = 'xlsx';
        $path = 'public/export/'.$filename.Str::random(40).'.'.$extension;
        $full_path = Storage::path($path);
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);
        $sheet_drugs = new Worksheet($spreadsheet, 'Drugs');

        $spreadsheet->addSheet($sheet_drugs);

        $results = [['DIN', 'Product_Description']];
        $max_disorders = 0;
        foreach ($this->readExcel($export->params['file']) as $data_row) {
            $drug = Drug::where('din_pin', str_pad($data_row[0], 8, '0', STR_PAD_LEFT))->first();
            $row = [];
            $row[] = str_pad($data_row[0], 8, '0', STR_PAD_LEFT);
            $row[] = $data_row[1];

            if ($drug) {
                if (sizeof($drug->disorders) > $max_disorders) {
                    $max_disorders = sizeof($drug->disorders);
                }
                foreach ($drug->disorders as $disorder) {
                    $row[] = $disorder->name;
                    $row[] = $disorder->category;
                }

                $row[] = $drug->strength;

                if (str_contains(strtolower($drug->active_ingredient), 'insulin')) {
                    $row[] = 'Y';
                } else {
                    $row[] = 'N';
                }
            } else {
                $row[] = '';
                $row[] = '';
            }

            $results[] = $row;
        }

        for ($i = 1; $i <= $max_disorders; ++$i) {
            $results[0][] = 'Sub_Med_Condition_'.$i;
            $results[0][] = 'Hub_Grouping_'.$i;
        }
        $results[0][] = 'Strength';
        $results[0][] = 'Insulin_Flag';

        foreach ($results as &$row) {
            if ((sizeof($row) - 4) / 2 <= $max_disorders) {
                $padding = array_fill(sizeof($row) - 2, $max_disorders + 4 + 8 - sizeof($row), '');
                $padding[] = $row[sizeof($row) - 2];
                $padding[] = $row[sizeof($row) - 1];

                $row = array_merge(array_slice($row, 0, sizeof($row) - 2, true), $padding);
            }
        }

        $sheet_drugs->fromArray($results);
        $this->prepareDrugsWorksheet($sheet_drugs);

        $spreadsheet->setActiveSheetIndex(0);
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($full_path);
        $this->SecureExcelFile($full_path, $export);
        $file = File::saveToDB($path, $filename.'.'.$extension);

        $export->file_id = $file->id;
        $export->status = 'FINISHED';

        $export->save();
    }

    public function prepareDrugsWorksheet($sheet)
    {
        $sheet->getStyle('1:1')->getFont()->setBold(true);
        $sheet->getColumnDimension('A')->setWidth(80, 'px');
        $sheet->getColumnDimension('B')->setWidth(200, 'px');

        $sheet->getStyle('A')->getNumberFormat()->setFormatCode('@');
        $sheet->setAutoFilter($sheet->calculateWorksheetDimension());
    }

    public function readExcel($file_id)
    {
        $file = File::find($file_id);

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file->getFileAbselutePath());
        $sheet = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());
        $data = $sheet->toArray();

        unset($data[0]);

        return $data;
    }
}
