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

class ExportCLICMaster extends ExportBase
{
    use AsAction;

    public function handle(Export $export)
    {
        $filename = 'CLIC Master Report Final ('.date('Ymd').')';
        $extension = 'xlsx';
        $xls_path = 'public/export/clic-master'.Str::random(40).'.'.$extension;
        $xls_full_path = Storage::path($xls_path);
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);

        $sheet_1 = new Worksheet($spreadsheet, 'Update #'.$export->getVersion().' for CLIC');

        $spreadsheet->addSheet($sheet_1);

        $this->prepareWorksheet($sheet_1);
        $xls_row = 2;
        foreach (Drug::select(['din_pin as DIN/PIN', 'drug_or_product_name as Drug or Product Name', 'clic_action as Action', 'explanation as Explanation', 'clic_tier as CLIC Tier', 'clic_gf_period as CLIC GF Period', 'clic_ql as CLIC QL', 'sa as SA (SA or blank)'])->where('clic', 1)->get() as $row) {
            $sheet_1
                ->setCellValue('A'.$xls_row, $row['DIN/PIN'])
                ->setCellValue('B'.$xls_row, $row['Drug or Product Name'])
                ->setCellValue('C'.$xls_row, $row['Action'])
                ->setCellValue('D'.$xls_row, $row['Explanation'])
                ->setCellValue('E'.$xls_row, $row['CLIC Tier'])
                ->setCellValue('F'.$xls_row, $row['CLIC GF Period'])
                ->setCellValue('G'.$xls_row, $row['CLIC QL'])
                ->setCellValue('H'.$xls_row, $row['SA (SA or Blank)'])
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
            ->setCellValue('B1', 'Drug or Product Name')
            ->setCellValue('C1', 'Action')
            ->setCellValue('D1', 'Explanation')
            ->setCellValue('E1', 'CLIC Tier')
            ->setCellValue('F1', 'CLIC GF Period')
            ->setCellValue('G1', 'CLIC QL')
            ->setCellValue('H1', 'SA (SA or Blank)')
        ;
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        $sheet->getColumnDimension('A')->setWidth(80, 'px');
        $sheet->getColumnDimension('B')->setWidth(630, 'px');
        $sheet->getColumnDimension('C')->setWidth(230, 'px');
        $sheet->getColumnDimension('D')->setWidth(680, 'px');
        $sheet->getColumnDimension('E')->setWidth(80, 'px');
        $sheet->getColumnDimension('F')->setWidth(120, 'px');
        $sheet->getColumnDimension('G')->setWidth(300, 'px');
        $sheet->getColumnDimension('H')->setWidth(120, 'px');

        $sheet->getStyle('A')->getNumberFormat()->setFormatCode('@');
        $sheet->getStyle('E')->getNumberFormat()->setFormatCode('@');

        $sheet->setAutoFilter($sheet->calculateWorksheetDimension());
    }
}
