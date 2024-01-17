<?php

namespace App\Actions\Exports;

use App\Models\Drug;
use App\Models\Disorder;
use App\Models\File;
use App\Models\Export;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportAll extends ExportBase
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
        $sheet_medical_conditions = new Worksheet($spreadsheet, 'Medical Conditions');

        $spreadsheet->addSheet($sheet_drugs);
        $spreadsheet->addSheet($sheet_medical_conditions);

        $this->prepareDrugsWorksheet($sheet_drugs);
        $this->prepareMedicalConditionsWorksheet($sheet_medical_conditions);

        $row_number = 2;
        foreach (Drug::all() as $drug) {
            $col = 1;
            foreach ($this->getDrugsColumns() as $column) {
                if ('disorders_ids' === $column->name) {
                    $sheet_drugs->getCell([$col, $row_number])->setValue(implode(',', $drug->disorders->pluck('id')->toArray()));
                } elseif ('sub_medical_conditions' === $column->name && 0 < $drug->disorders->count()) {
                    $sheet_drugs->getCell([$col, $row_number])->setValue('"'.implode('","', $drug->disorders->pluck('name')->toArray()).'"');
                } else {
                    $value = $drug->getAttribute($column->name);
                    if (true === $value) {
                        $value = 'Y';
                    } elseif (false === $value) {
                        $value = 'N';
                    }
                    $sheet_drugs->getCell([$col, $row_number])->setValue($value);
                }

                ++$col;
            }
            ++$row_number;
        }

        $row_number = 2;
        foreach (Disorder::all() as $disorder) {
            $col = 1;
            $sheet_medical_conditions->getCell([$col++, $row_number])->setValue($disorder->id);
            $sheet_medical_conditions->getCell([$col++, $row_number])->setValue($disorder->name);
            $sheet_medical_conditions->getCell([$col++, $row_number])->setValue($disorder->category);
            ++$row_number;
        }

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
        $col = 1;
        foreach ($this->getDrugsColumns() as $column) {
            $sheet->getCellByColumnAndRow($col, 1)->setValue($column->name);
            $sheet->getColumnDimension($sheet->getCellByColumnAndRow($col, 1)->getColumn())->setWidth(120, 'px');
            // $sheet->getStyle($sheet->getCellByColumnAndRow($col, 1)->getColumn().'1')->getFont()->setBold(true);
            ++$col;
        }
        $sheet->getStyle($sheet->calculateWorksheetDimension())->getFont()->setBold(true);
        $sheet->getColumnDimension('A')->setWidth(80, 'px');
        $sheet->getColumnDimension('B')->setWidth(80, 'px');
        $sheet->getColumnDimension('C')->setWidth(80, 'px');

        $sheet->getStyle('D')->getNumberFormat()->setFormatCode('@');
        $sheet->getStyle('G')->getNumberFormat()->setFormatCode('@');
        $sheet->getStyle('H')->getNumberFormat()->setFormatCode('@');
        $sheet->getStyle('I')->getNumberFormat()->setFormatCode('@');

        $sheet->setAutoFilter($sheet->calculateWorksheetDimension());
    }

    public function prepareMedicalConditionsWorksheet($sheet)
    {
        $col = 1;
        $sheet->getCell([$col++, 1])->setValue('ID');
        $sheet->getCell([$col++, 1])->setValue('Name (Sub-medical Condition)');
        $sheet->getCell([$col++, 1])->setValue('Category (Medical Condition)');

        $sheet->getStyle($sheet->calculateWorksheetDimension())->getFont()->setBold(true);
        $sheet->getColumnDimension('A')->setWidth(80, 'px');
        $sheet->getColumnDimension('B')->setWidth(360, 'px');
        $sheet->getColumnDimension('C')->setWidth(300, 'px');

        $sheet->setAutoFilter($sheet->calculateWorksheetDimension());
    }

    public function getDrugsColumns()
    {
        static $rc = [];

        if ($rc) {
            return $rc;
        }

        $disorders_ids_inserted = false;
        $disorders_inserted = false;
        foreach (Drug::first()->getAttributes() as $k => $v) {
            if ('medical_condition' == $k) {
                $rc[] = (object) ['name' => 'disorders_ids'];
                $disorders_ids_inserted = true;
            } elseif ('sub_medical_condition' == $k) {
                $rc[] = (object) ['name' => 'sub_medical_conditions'];
                $disorders_inserted = true;
            } else {
                $rc[] = (object) ['name' => $k];
            }
        }

        if (!$disorders_ids_inserted) {
            $rc[] = (object) ['name' => 'disorders_ids'];
        }
        if (!$disorders_inserted) {
            $rc[] = (object) ['name' => 'sub_medical_conditions'];
        }

        return $rc;
    }
}
