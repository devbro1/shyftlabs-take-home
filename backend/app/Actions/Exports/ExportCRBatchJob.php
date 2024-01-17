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

class ExportCRBatchJob extends ExportBase
{
    use AsAction;

    public function handle(Export $export)
    {
        $filename = Str::kebab('ChangeRequestBatchJob ('.date('M d, Y H:m').')');
        $extension = 'xlsx';
        $xls_path = 'public/export/change-requests-'.Str::random(40).'.'.$extension;
        $xls_full_path = Storage::path($xls_path);
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);

        $sheet_1 = new Worksheet($spreadsheet, 'Change Requests');

        $spreadsheet->addSheet($sheet_1);

        $this->prepareWorksheet($sheet_1);
        $xls_row = 2;
        foreach ($export->params as $reason => $changeRequests) {
            foreach ($changeRequests as $cr) {
                $drug = Drug::find($cr['drug_id']);
                $sheet_1
                    ->setCellValue('A'.$xls_row, $cr['id'])
                    ->setCellValue('B'.$xls_row, $reason)
                    ->setCellValue('C'.$xls_row, $cr['changes']['din_pin'] ?? $drug->din_pin ?? '')
                    ->setCellValue('D'.$xls_row, $cr['changes']['drug_or_product_name'] ?? $drug->drug_or_product_name ?? '')
                    ->setCellValue('E'.$xls_row, $cr['changes']['message'] ?? '')
                ;

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
            ->setCellValue('A1', 'Change Request ID')
            ->setCellValue('B1', 'action')
            ->setCellValue('C1', 'din_pin')
            ->setCellValue('D1', 'drug_or_product_name')
            ->setCellValue('E1', 'message')
        ;
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        $sheet->getColumnDimension('A')->setWidth(80, 'px');
        $sheet->getColumnDimension('B')->setWidth(330, 'px');
        $sheet->getColumnDimension('C')->setWidth(180, 'px');
        $sheet->getColumnDimension('D')->setWidth(180, 'px');
        $sheet->getColumnDimension('D')->setWidth(180, 'px');

        $sheet->getStyle('C')->getNumberFormat()->setFormatCode('@');

        $sheet->setAutoFilter($sheet->calculateWorksheetDimension());
    }
}
