<?php

namespace App\Actions\Exports;

use App\Models\ChangeRequest;
use App\Models\Drug;
use App\Models\Export;
use App\Models\File;
use App\Models\Translation;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportChangeRequests extends ExportBase
{
    use AsAction;

    private $file_name;

    public function handle(Export $export)
    {
        $this->file_name = 'Change Requests ('.date('Ymd').')';
        $change_requests = ChangeRequest::where('status', 'PENDING')->orWhere('updated_at', '>', Carbon::now()->subDays(30));
        $this->processRequest($export, $change_requests);
    }

    public function processRequest($export, $change_requests)
    {
        $filename = Str::kebab($this->file_name);
        $extension = 'xlsx';
        $xls_path = 'public/export/change-requests-'.Str::random(40).'.'.$extension;
        $xls_full_path = Storage::path($xls_path);
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);

        $sheet_1 = new Worksheet($spreadsheet, 'Change Requests');
        $spreadsheet->addSheet($sheet_1);

        $this->prepareWorksheet($sheet_1);

        $row_number = 2;
        foreach ($change_requests->get() as $cr) {
            $drug = $cr->drug;

            $drug_data = $this->generateRow($cr, $drug);
            $this->WriteDataRow($sheet_1, $row_number++, $drug_data);
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($xls_full_path);
        $this->SecureExcelFile($xls_full_path, $export);
        $file = File::saveToDB($xls_path, $filename.'.'.$extension);

        $export->file_id = $file->id;
        $export->status = 'FINISHED';

        $export->save();
    }

    public function WriteDataRow($sheet, $row_number, $row_data)
    {
        $col = 1;
        foreach ($this->getColumns() as $data_col) {
            if (isset(json_decode($row_data['change_request_changes'], true)[$data_col['db_col_name']])) {
                $change_value = json_decode($row_data['change_request_changes'], true)[$data_col['db_col_name']] ?? '';

                $sheet->getCellByColumnAndRow($col, $row_number)->setValue($change_value);
                $sheet->getCellByColumnAndRow($col, $row_number)->getStyle()
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FCD5B4')
                ;

                if ($row_data[$data_col['db_col_name']] ?? false) {
                    $sheet->getCommentByColumnAndRow($col, $row_number)
                        ->getText()->createTextRun('Previous Value: '.($row_data[$data_col['db_col_name']] ?? ''))
                    ;
                }

                ++$col;
            } else {
                $sheet->getCellByColumnAndRow($col++, $row_number)->setValue($row_data[$data_col['db_col_name']] ?? '');
            }
        }
    }

    public function prepareWorksheet($sheet)
    {
        $col = 1;
        foreach ($this->getColumns() as $data_col) {
            $sheet->getCellByColumnAndRow($col, 1)->setValue($data_col['db_col_name']);
            $sheet->getColumnDimension($sheet->getCellByColumnAndRow($col, 1)->getColumn())->setWidth(120, 'px');
            ++$col;
        }

        $sheet->getStyle($sheet->calculateWorksheetDimension())->getFont()->setBold(true);
        $sheet->setAutoFilter($sheet->calculateWorksheetDimension());
    }

    public function t($str)
    {
        $t = Translation::where('language', 'fr')->where('namespace', 'drug')->where('key', $str)->first();

        if ($t) {
            return $t->translation;
        }

        return $str;
    }

    public function getColumns()
    {
        static $rc = null;

        if ($rc) {
            return $rc;
        }
        $cr_cols = [];
        $cr_cols[] = ['excel_col_name' => 'Change Request ID', 'db_col_name' => 'change_request_id', 'field_source' => 'change_request'];
        $cr_cols[] = ['excel_col_name' => 'created at', 'db_col_name' => 'change_request_created_at', 'field_source' => 'change_request'];
        $cr_cols[] = ['excel_col_name' => 'updated at', 'db_col_name' => 'change_request_updated_at', 'field_source' => 'change_request'];
        $cr_cols[] = ['excel_col_name' => 'drug_id', 'db_col_name' => 'change_request_drug_id', 'field_source' => 'change_request'];
        $cr_cols[] = ['excel_col_name' => 'changes', 'db_col_name' => 'change_request_changes', 'field_source' => 'change_request'];
        $cr_cols[] = ['excel_col_name' => 'status', 'db_col_name' => 'change_request_status', 'field_source' => 'change_request'];
        $cr_cols[] = ['excel_col_name' => 'message', 'db_col_name' => 'change_request_message', 'field_source' => 'change_request'];
        $cr_cols[] = ['excel_col_name' => 'source', 'db_col_name' => 'change_request_source', 'field_source' => 'change_request'];

        $drug_cols = [];
        $drug = Drug::first();
        foreach ($drug->getAttributes() as $key => $attr) {
            $drug_cols[] = ['excel_col_name' => $key, 'db_col_name' => $key, 'field_source' => 'drug'];
        }

        $rc = array_merge($cr_cols, $drug_cols);

        return $rc;
    }

    public function generateRow($cr, $drug)
    {
        $drug_attributes = [];
        $cr_attributes = $cr->getAttributes();
        foreach ($cr_attributes as $k => $v) {
            $cr_attributes['change_request_'.$k] = $v;
            unset($cr_attributes[$k]);
        }

        if ($drug) {
            $drug_attributes = $drug->getAttributes();
        }

        return array_merge($cr_attributes, $drug_attributes);
    }
}
