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

class ExportCanadaLife extends ExportBase
{
    use AsAction;

    public function handle(Export $export)
    {
        $filename = 'Reformulary ('.date('M d, Y').')';
        $extension = 'xlsx';
        $xls_path = 'public/export/canada-life-'.Str::random(40).'.'.$extension;
        $xls_full_path = Storage::path($xls_path);
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);

        $sheet_1 = new Worksheet($spreadsheet, 'Tier 1');
        $sheet_2 = new Worksheet($spreadsheet, 'Tier 2');
        $sheet_3 = new Worksheet($spreadsheet, 'Tier 3');
        $sheet_NONE = new Worksheet($spreadsheet, 'None');
        $sheet_SA = new Worksheet($spreadsheet, 'SA');

        $spreadsheet->addSheet($sheet_1);
        $spreadsheet->addSheet($sheet_2);
        $spreadsheet->addSheet($sheet_3);
        $spreadsheet->addSheet($sheet_NONE);
        $spreadsheet->addSheet($sheet_SA);

        $this->prepareWorksheet($sheet_1);
        $xls_row = 2;
        foreach (Drug::select(['din_pin as DIN/PIN', 'drug_or_product_name as Drug or Product Name', 'gwl_tier as GWL Tier'])->where('gwl', 1)->where('gwl_tier', '1')->get() as $row) {
            $sheet_1
                ->setCellValue('A'.$xls_row, $row['DIN/PIN'])
                ->setCellValue('B'.$xls_row, $row['Drug or Product Name'])
                ->setCellValue('C'.$xls_row, $row['GWL Tier'])
            ;

            ++$xls_row;
        }

        $this->prepareWorksheet($sheet_2);
        $xls_row = 2;
        foreach (Drug::select(['din_pin as DIN/PIN', 'drug_or_product_name as Drug or Product Name', 'gwl_tier as GWL Tier'])->where('gwl', 1)->where('gwl_tier', '2')->get() as $row) {
            $sheet_2
                ->setCellValue('A'.$xls_row, $row['DIN/PIN'])
                ->setCellValue('B'.$xls_row, $row['Drug or Product Name'])
                ->setCellValue('C'.$xls_row, $row['GWL Tier'])
            ;

            ++$xls_row;
        }

        $this->prepareWorksheet($sheet_3);
        $xls_row = 2;
        foreach (Drug::select(['din_pin as DIN/PIN', 'drug_or_product_name as Drug or Product Name', 'gwl_tier as GWL Tier'])->where('gwl', 1)->where('gwl_tier', '3')->get() as $row) {
            $sheet_3
                ->setCellValue('A'.$xls_row, $row['DIN/PIN'])
                ->setCellValue('B'.$xls_row, $row['Drug or Product Name'])
                ->setCellValue('C'.$xls_row, $row['GWL Tier'])
            ;

            ++$xls_row;
        }

        $this->prepareWorksheet($sheet_NONE);
        $xls_row = 2;
        foreach (Drug::select(['din_pin as DIN/PIN', 'drug_or_product_name as Drug or Product Name', 'gwl_tier as GWL Tier'])->where('gwl', 1)->where('gwl_tier', 'ilike', 'NONE%')->get() as $row) {
            $sheet_NONE
                ->setCellValue('A'.$xls_row, $row['DIN/PIN'])
                ->setCellValue('B'.$xls_row, $row['Drug or Product Name'])
                ->setCellValue('C'.$xls_row, $row['GWL Tier'])
            ;

            ++$xls_row;
        }

        $this->prepareWorksheet($sheet_SA);
        $xls_row = 2;
        foreach (Drug::select(['din_pin as DIN/PIN', 'drug_or_product_name as Drug or Product Name', 'gwl_tier as GWL Tier'])->where('gwl', 1)->where('gwl_tier', 'ilike', 'SA%')->get() as $row) {
            $sheet_SA
                ->setCellValue('A'.$xls_row, $row['DIN/PIN'])
                ->setCellValue('B'.$xls_row, $row['Drug or Product Name'])
                ->setCellValue('C'.$xls_row, $row['GWL Tier'])
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
            ->setCellValue('C1', 'GWL Tier')
        ;
        $sheet->getStyle('A1:C1')->getFont()->setBold(true);
        $sheet->getColumnDimension('A')->setWidth(80, 'px');
        $sheet->getColumnDimension('B')->setWidth(450, 'px');
        $sheet->getColumnDimension('C')->setWidth(80, 'px');

        $sheet->getStyle('A')->getNumberFormat()->setFormatCode('@');
        $sheet->getStyle('C')->getNumberFormat()->setFormatCode('@');

        $sheet->setAutoFilter($sheet->calculateWorksheetDimension());
    }
}
