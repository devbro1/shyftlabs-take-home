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

class ExportJGMaster extends ExportBase
{
    use AsAction;

    public function handle(Export $export)
    {
        $filename = Str::kebab('JG Master Report Final ('.date('Ymd').')');
        $extension = 'xlsx';
        $xls_path = 'public/export/jg-master-'.Str::random(40).'.'.$extension;
        $xls_full_path = Storage::path($xls_path);
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);

        $sheet_1 = new Worksheet($spreadsheet, 'JGTier1');
        $sheet_2 = new Worksheet($spreadsheet, 'JGTier2');
        $sheet_3 = new Worksheet($spreadsheet, 'JGTier3');

        $spreadsheet->addSheet($sheet_1);
        $spreadsheet->addSheet($sheet_2);
        $spreadsheet->addSheet($sheet_3);

        $list = [];
        $list[] = ['sheet' => $sheet_1, 'jg_tier' => 1];
        $list[] = ['sheet' => $sheet_2, 'jg_tier' => 2];
        $list[] = ['sheet' => $sheet_3, 'jg_tier' => 3];

        foreach ($list as $item) {
            $this->prepareWorksheet($item['sheet']);
            $xls_row = 2;
            foreach (Drug::select(['din_pin as DIN/PIN', 'drug_or_product_name as Drug or Product Name', 'jg_tier as JG Tier', 'jg_gf_period as JG GF Period', 'jg_ql as JG QL', 'jg_action as Action', 'explanation as Explanation'])->where('jg', 1)->where('jg_tier', $item['jg_tier'])->get() as $row) {
                $item['sheet']
                    ->setCellValue('A'.$xls_row, $row['DIN/PIN'])
                    ->setCellValue('B'.$xls_row, $row['Drug or Product Name'])
                    ->setCellValue('C'.$xls_row, $row['JG Tier'])
                    ->setCellValue('D'.$xls_row, $row['JG GF Period'])
                    ->setCellValue('E'.$xls_row, $row['JG QL'])
                    ->setCellValue('F'.$xls_row, $row['Action'])
                    ->setCellValue('G'.$xls_row, $row['Explanation']);

                ++$xls_row;
            }
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
            ->setCellValue('C1', 'JG Tier')
            ->setCellValue('D1', 'JG GF Period')
            ->setCellValue('E1', 'JG QL')
            ->setCellValue('F1', 'Action')
            ->setCellValue('G1', 'Explanation')
        ;
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        $sheet->getColumnDimension('A')->setWidth(80, 'px');
        $sheet->getColumnDimension('B')->setWidth(450, 'px');
        $sheet->getColumnDimension('C')->setWidth(70, 'px');
        $sheet->getColumnDimension('D')->setWidth(100, 'px');
        $sheet->getColumnDimension('E')->setWidth(60, 'px');
        $sheet->getColumnDimension('F')->setWidth(60, 'px');
        $sheet->getColumnDimension('G')->setWidth(260, 'px');

        $sheet->getStyle('A')->getNumberFormat()->setFormatCode('@');

        $sheet->setAutoFilter($sheet->calculateWorksheetDimension());
    }
}
