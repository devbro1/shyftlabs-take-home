<?php

namespace App\Actions\Exports;

use App\Models\Translation;
use App\Models\File;
use App\Models\Export;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportTranslations extends ExportBase
{
    use AsAction;

    public function handle(Export $export)
    {
        $filename = Str::kebab('translations-'.date('Y-m-d').'');
        $extension = 'xlsx';
        $xls_path = 'public/export/translations-'.Str::random(40).'.'.$extension;
        $xls_full_path = Storage::path($xls_path);
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);

        $sheet_1 = new Worksheet($spreadsheet, 'translations');

        $spreadsheet->addSheet($sheet_1);

        $this->prepareWorksheet($sheet_1);
        $xls_row = 2;
        foreach (Translation::all() as $row) {
            $sheet_1
                ->setCellValue('A'.$xls_row, $row['id'])
                ->setCellValue('B'.$xls_row, $row['created_at'])
                ->setCellValue('C'.$xls_row, $row['updated_at'])
                ->setCellValue('D'.$xls_row, $row['key'])
                ->setCellValue('E'.$xls_row, $row['namespace'])
                ->setCellValue('F'.$xls_row, $row['language'])
                ->setCellValue('G'.$xls_row, $row['translation'])
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
            ->setCellValue('A1', 'ID')
            ->setCellValue('B1', 'created_at')
            ->setCellValue('C1', 'updated_at')
            ->setCellValue('D1', 'key')
            ->setCellValue('E1', 'namespace')
            ->setCellValue('F1', 'language')
            ->setCellValue('G1', 'translation')
        ;
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        $sheet->getColumnDimension('A')->setWidth(80, 'px');
        $sheet->getColumnDimension('B')->setWidth(80, 'px');
        $sheet->getColumnDimension('C')->setWidth(80, 'px');
        $sheet->getColumnDimension('D')->setWidth(200, 'px');
        $sheet->getColumnDimension('E')->setWidth(80, 'px');
        $sheet->getColumnDimension('F')->setWidth(80, 'px');
        $sheet->getColumnDimension('G')->setWidth(300, 'px');

        $sheet->setAutoFilter($sheet->calculateWorksheetDimension());
    }
}
