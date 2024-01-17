<?php

namespace App\Actions\Exports;

use App\Models\Drug;
use App\Models\File;
use App\Models\Export;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Str;

class ExportSLFMaster extends ExportBase
{
    use AsAction;

    public function handle(Export $export)
    {
        $filename = 'SLF Master Report Final ('.date('Ymd').')';
        $extension = 'xlsx';
        $xls_path = 'public/export/slf-master-'.Str::random(40).'.'.$extension;
        $xls_full_path = Storage::path($xls_path);
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);

        $sheet_R = new Worksheet($spreadsheet, 'Reformulary');
        $sheet_1 = new Worksheet($spreadsheet, 'EB01');
        $sheet_2 = new Worksheet($spreadsheet, 'EB02');
        $sheet_3 = new Worksheet($spreadsheet, 'EB03');
        $sheet_NONE = new Worksheet($spreadsheet, 'None');
        $sheet_SA = new Worksheet($spreadsheet, 'SA');
        $sheet_PREXD = new Worksheet($spreadsheet, 'PREX D');

        $spreadsheet->addSheet($sheet_R);
        $spreadsheet->addSheet($sheet_1);
        $spreadsheet->addSheet($sheet_2);
        $spreadsheet->addSheet($sheet_3);
        $spreadsheet->addSheet($sheet_NONE);
        $spreadsheet->addSheet($sheet_SA);
        $spreadsheet->addSheet($sheet_PREXD);

        $list = [];
        $list[] = ['sheet' => $sheet_R, 'tier' => null];
        $list[] = ['sheet' => $sheet_1, 'tier' => 1];
        $list[] = ['sheet' => $sheet_2, 'tier' => 2];
        $list[] = ['sheet' => $sheet_3, 'tier' => 3];
        $list[] = ['sheet' => $sheet_NONE, 'tier' => 'None'];
        $list[] = ['sheet' => $sheet_SA, 'tier' => 'SA'];
        $list[] = ['sheet' => $sheet_PREXD, 'tier' => 'PREX D'];

        foreach ($list as $item) {
            $this->prepareWorksheet($item['sheet']);
            $xls_row = 2;
            $q = Drug::select(['din as DIN', 'pin as PIN', 'drug_or_product_name as Drug or Product Name', 'drug_class as Drug Class', 'active_ingredient as Active Ingredient', 'slf_action as Action', 'explanation as Explanation', 'slf_tier as SLF Tier', 'slf_gf_period as SLF GF Period', 'slf_ql as SLF QL', 'strength as Strength', 'form as Form', 'discontinued_date as Discontinued date']);
            $q = $q->where('slf', 1);
            if ($item['tier']) {
                $q = $q->where('slf_tier', $item['tier']);
            }
            foreach ($q->get() as $row) {
                $item['sheet']
                    ->setCellValue('A'.$xls_row, $row['DIN'])
                    ->setCellValue('B'.$xls_row, $row['PIN'])
                    ->setCellValue('C'.$xls_row, $row['Drug or Product Name'])
                    ->setCellValue('D'.$xls_row, $row['Drug Class'])
                    ->setCellValue('E'.$xls_row, $row['Active Ingredient'])
                    ->setCellValue('F'.$xls_row, $row['Action'])
                    ->setCellValue('G'.$xls_row, $row['Explanation'])
                    ->setCellValue('H'.$xls_row, $row['SLF Tier'])
                    ->setCellValue('I'.$xls_row, $row['SLF GF Period'])
                    ->setCellValue('J'.$xls_row, $row['SLF QL'])
                    ->setCellValue('K'.$xls_row, $row['Strength'])
                    ->setCellValue('L'.$xls_row, $row['Form'])
                    ->setCellValue('M'.$xls_row, $row['Discontinued date']);

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
            ->setCellValue('A1', 'DIN')
            ->setCellValue('B1', 'PIN')
            ->setCellValue('C1', 'Drug or Product Name')
            ->setCellValue('D1', 'Drug Class')
            ->setCellValue('E1', 'Active Ingredient')
            ->setCellValue('F1', 'Action')
            ->setCellValue('G1', 'Explanation')
            ->setCellValue('H1', 'SLF Tier')
            ->setCellValue('I1', 'SLF GF Period')
            ->setCellValue('J1', 'SLF QL')
            ->setCellValue('K1', 'Strength')
            ->setCellValue('L1', 'Form')
            ->setCellValue('M1', 'Discontinued date')
        ;
        $sheet->getStyle($sheet->calculateWorksheetDimension())->getFont()->setBold(true);
        $sheet->getColumnDimension('A')->setWidth(80, 'px');
        $sheet->getColumnDimension('B')->setWidth(80, 'px');
        $sheet->getColumnDimension('C')->setWidth(80, 'px');
        $sheet->getColumnDimension('D')->setWidth(80, 'px');
        $sheet->getColumnDimension('E')->setWidth(80, 'px');
        $sheet->getColumnDimension('F')->setWidth(80, 'px');
        $sheet->getColumnDimension('G')->setWidth(80, 'px');
        $sheet->getColumnDimension('H')->setWidth(80, 'px');
        $sheet->getColumnDimension('I')->setWidth(80, 'px');
        $sheet->getColumnDimension('J')->setWidth(80, 'px');
        $sheet->getColumnDimension('K')->setWidth(80, 'px');
        $sheet->getColumnDimension('L')->setWidth(80, 'px');
        $sheet->getColumnDimension('M')->setWidth(80, 'px');

        $sheet->getStyle('A')->getNumberFormat()->setFormatCode('@');
        $sheet->getStyle('B')->getNumberFormat()->setFormatCode('@');

        $sheet->setAutoFilter($sheet->calculateWorksheetDimension());
    }
}
