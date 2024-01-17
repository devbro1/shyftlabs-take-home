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

class ExportCLICNexGen extends ExportBase
{
    use AsAction;

    public function handle(Export $export)
    {
        $filename = 'CLIC NexGen Report Final ('.date('Ymd').')';
        $extension = 'xlsx';
        $xls_path = 'public/export/clic-nexgen'.Str::random(40).'.'.$extension;
        $xls_full_path = Storage::path($xls_path);
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);

        $sheet_1 = new Worksheet($spreadsheet, 'Update #'.$export->getVersion().' for NexGen');

        $spreadsheet->addSheet($sheet_1);

        $this->prepareWorksheet($sheet_1);
        $xls_row = 2;
        foreach (Drug::select(['din_pin as DIN/PIN', 'clic_tier as CLIC Tier'])->where('clic', 1)->get() as $row) {
            $sheet_1
                ->setCellValue('A'.$xls_row, $row['DIN/PIN'])
                ->setCellValue('B'.$xls_row, $row['CLIC Tier'])
            ;

            ++$xls_row;
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($xls_full_path);
        $this->SecureExcelFile($xls_full_path, $export);
        $file = File::saveToDB($xls_path, $filename.'.'.$extension);

        $export->file_id = $file->id;
        $export->status = 'FINISHED';

        $export->save();
    }

    public function prepareWorksheet($sheet)
    {
        $sheet
            ->setCellValue('A1', 'DIN/PIN')
            ->setCellValue('B1', 'CLIC Tier')
        ;
        $sheet->getStyle('A1:B1')->getFont()->setBold(true);
        $sheet->getColumnDimension('A')->setWidth(80, 'px');
        $sheet->getColumnDimension('B')->setWidth(80, 'px');

        $sheet->getStyle('A')->getNumberFormat()->setFormatCode('@');
        $sheet->getStyle('B')->getNumberFormat()->setFormatCode('@');

        $sheet->setAutoFilter($sheet->calculateWorksheetDimension());
    }
}
