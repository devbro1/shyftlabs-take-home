<?php

namespace App\Actions\Exports;

use App\Models\Drug;
use App\Models\Export;
use App\Models\File;
use App\Models\Translation;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportDrugFinderNew extends ExportBase
{
    use AsAction;

    protected $file_name;

    public function handle(Export $export)
    {
        $this->file_name = 'drugfinder new Export '.date('Ymd').'';
        $drugs = Drug::where('ref_id', '');
        $this->processRequest($export, $drugs);
    }

    public function processRequest($export, $drugs)
    {
        $filename = Str::kebab($this->file_name);
        $extension = 'xlsx';
        $xls_path = 'public/export/canada-life-'.Str::random(40).'.'.$extension;

        $xls_full_path = Storage::path($xls_path);
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);

        $sheet_1 = new Worksheet($spreadsheet, 'DrugFinder Data');
        $spreadsheet->addSheet($sheet_1);

        $this->prepareWorksheet($sheet_1);

        $row_number = 2;
        foreach ($drugs->get() as $drug) {
            $ref_id = $drug->id * 100;

            if (count($drug->disorders)) {
                foreach ($drug->disorders as $disorder) {
                    $drug_data = $this->generateRow($drug, $ref_id, $disorder);
                    $this->WriteDataRow($sheet_1, $row_number++, $drug_data);

                    ++$ref_id;
                }
            } else {
                $drug_data = $this->generateRow($drug, $ref_id);
                $this->WriteDataRow($sheet_1, $row_number++, $drug_data);
            }
        }

        Drug::where('ref_id', '')->update(['ref_id' => DB::raw('id * 100')]);

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($xls_full_path);
        $this->SecureExcelFile($xls_full_path, $export);
        $file = File::saveToDB($xls_path, $filename.'.'.$extension);

        $export->file_id = $file->id;
        $export->status = 'FINISHED';

        $export->save();
    }

    public function WriteDataRow($sheet, $row_number, $drug_data)
    {
        $col = 1;
        foreach ($this->getColumns() as $column_name) {
            $sheet->getCellByColumnAndRow($col++, $row_number)->setValue($drug_data[$column_name]);
        }
    }

    public function prepareWorksheet($sheet)
    {
        $col = 1;
        foreach ($this->getColumns() as $title) {
            $sheet->getCellByColumnAndRow($col, 1)->setValue($title);
            $sheet->getColumnDimension($sheet->getCellByColumnAndRow($col, 1)->getColumn())->setWidth(120, 'px');
            // $sheet->getStyle($sheet->getCellByColumnAndRow($col, 1)->getColumn().'1')->getFont()->setBold(true);
            ++$col;
        }

        $sheet->getStyle($sheet->calculateWorksheetDimension())->getFont()->setBold(true);

        $sheet->getStyle('D')->getNumberFormat()->setFormatCode('@');
        $sheet->getStyle('E')->getNumberFormat()->setFormatCode('@');
        $sheet->getStyle('F')->getNumberFormat()->setFormatCode('@');

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
        return ['RefID', 'Modified y/N', 'Alert Notifications', 'DIN', 'PIN', 'DIN/PIN', 'Drug or Product Name', 'DIN Duplicate (D)', 'Health Canada Drug Name', 'Reformulary (Y/N)', 'RG Tier', 'Conditions (Y/N)', 'Drug/Product Type', 'Medical Condition', 'Sub-medical Condition', 'Drug Class', 'Active Ingredient', 'GF Period Code', 'GF Period', 'Step Therapy (Y/N)', 'Quantity Limits', 'Action', 'Explanation', 'SLF (Y/N)', 'SLF Tier', 'SLF Action', 'SLF GF Period', 'SLF QL', 'SLF GF Period Code', 'SLF ScreenCode', 'SLF Visible on website', 'SLF Rationale Code', 'CLIC (Y/N)', 'CLIC Tier', 'CLIC Action', 'CLIC GF Period', 'CLIC Targetted letter (Y/X)', 'CLIC QL', 'Include/Exclude', 'SA (SA or blank)', 'CLIC GF Period Code', 'CLIC ScreenCode', 'CLIC Visible on website', 'CLIC Rationale Code', 'GWL (Y/N)', 'GWL Tier', 'GWL Action', 'GWL GF Period Code', 'GWL ScreenCode', 'GWL Visible on website', 'GWL Rationale Code', 'GWL (CADA) (Y/N)', 'GWL (CADA) Tier', 'GWL (CADA) Action', 'GWL (CADA) ScreenCode', 'GWL (CADA) Visible on website', 'GWL (CADA) Rationale Code', 'CS Y/N', 'CS Tier', 'CS Action', 'CS GF Period', 'CS GF Period Code', 'CS ScreenCode', 'CS Visible on website', 'CS Rationale Code', 'CS Notes', 'CS (IW) Y/N', 'CS (IW) Tier', 'CS (IW) Action', 'CS (IW) GF Period', 'CS (IW) GF Period Code', 'CS (IW) ScreenCode', 'CS (IW) Visible on website', 'CS (IW) Rationale Code', 'JG (Y/N)', 'JG Tier', 'JG SA', 'JG Notes', 'JG Action', 'JG Explanation', 'JG GF Period', 'JG QL', 'JG GF Period Code', 'JG ScreenCode', 'JG Visible on website', 'JG Rationale Code', 'Generic Name', 'Generic Version of', 'Screen Code', 'NewScreenCode 1', 'Visible on website', 'Blue Box', 'Alternative DINs', 'Alternative DINs Non-prescribed', 'Strength', 'Form', 'Route of Administration', 'Drug Sub-Type', 'Manufacturer', 'Discontinued date', 'Notes', 'RAMQ (Y/N/SA)', 'AHFS', 'DC', 'Schedule', 'A/M', 'TC', 'Life Sustaining OTC (Y/N)', 'Life Style Drug (Y/N)', 'Nom du médicament ou du produit', 'Position sur Reformulary', 'Affection médicale', 'Sous-affection médicale', 'Classe de médicaments', 'Ingrédient actif', 'Produit générique', 'Produit innovateur de référence', 'Boîte bleue', 'Alternative DINs Non-prescribed (Fre)', 'Teneur', 'Forme pharmaceutique', 'Test Strip', 'PIN SLF', 'PIN CLIC', 'Gx  available on the market', 'Notes on RG Select', 'Vaccines (used to protect against)', 'Vaccines (used to protect against) (Fre)', 'Rationale Code', 'Rationale (Show me "WHY" button) (Eng)', 'Rationale (Show me "WHY" button) (Fre)', 'Quantity Limits days', 'Used For Code', 'Specialty drug', 'SLFU', 'PREXDU', 'Patient Support Program', 'Special Distribution Program'];
    }

    public function generateRow($drug, $ref_id, $disorder = null)
    {
        $drug_data = []; // $drug->toArray();
        $drug_data['RefID'] = $ref_id;
        $drug_data['Modified y/N'] = 'Y';
        $drug_data['Alert Notifications'] = $drug->alert_notifications;
        $drug_data['DIN'] = ($drug->din) ? str_pad($drug->din, 8, '0', STR_PAD_RIGHT) : '';
        $drug_data['PIN'] = $drug->pin;
        $drug_data['DIN/PIN'] = ($drug->din_pin) ? str_pad($drug->din_pin, 8, '0', STR_PAD_RIGHT) : '';
        $drug_data['Drug or Product Name'] = $drug->drug_or_product_name;
        $drug_data['DIN Duplicate (D)'] = ($ref_id % 100) ? 'D' : '';
        $drug_data['Health Canada Drug Name'] = $drug->health_canada_drug_name;
        $drug_data['Reformulary (Y/N)'] = ($drug->reformulary) ? 'Y' : 'N';
        $drug_data['RG Tier'] = $drug->rg_tier;
        $drug_data['Conditions (Y/N)'] = ($drug->conditions) ? 'Y' : 'N';
        $drug_data['Medical Condition'] = ($disorder) ? $disorder->category : '';
        $drug_data['Sub-medical Condition'] = ($disorder) ? $disorder->name : '';
        $drug_data['Drug/Product Type'] = $drug->drug_product_type;
        $drug_data['Drug Class'] = $drug->drug_class;
        $drug_data['Active Ingredient'] = $drug->active_ingredient;
        $drug_data['GF Period Code'] = $drug->gf_period_code;
        $drug_data['GF Period'] = $drug->gf_period;
        $drug_data['Step Therapy (Y/N)'] = $drug->step_therapy;
        $drug_data['Quantity Limits'] = $drug->quantity_limits;
        $drug_data['Action'] = $drug->action;
        $drug_data['Explanation'] = $drug->explanation;
        $drug_data['SLF (Y/N)'] = ($drug->slf) ? 'Y' : 'N';
        $drug_data['SLF Tier'] = $drug->slf_tier;
        $drug_data['SLF Action'] = $drug->slf_action;
        $drug_data['SLF GF Period'] = $drug->slf_gf_period;
        $drug_data['SLF QL'] = $drug->slf_ql;
        $drug_data['SLF GF Period Code'] = $drug->slf_gf_period_code;
        $drug_data['SLF ScreenCode'] = $drug->slf_screencode;
        $drug_data['SLF Visible on website'] = $drug->slf_visible_on_website;
        $drug_data['SLF Rationale Code'] = $drug->slf_rationale_code;
        $drug_data['CLIC (Y/N)'] = ($drug->clic) ? 'Y' : 'N';
        $drug_data['CLIC Tier'] = $drug->clic_tier;
        $drug_data['CLIC Action'] = $drug->clic_action;
        $drug_data['CLIC GF Period'] = $drug->clic_gf_period;
        $drug_data['CLIC Targetted letter (Y/X)'] = $drug->clic_targetted_letter;
        $drug_data['CLIC QL'] = $drug->clic_ql;
        $drug_data['Include/Exclude'] = $drug->include_exclude;
        $drug_data['SA (SA or blank)'] = $drug->sa;
        $drug_data['CLIC GF Period Code'] = $drug->clic_gf_period_code;
        $drug_data['CLIC ScreenCode'] = $drug->clic_screencode;
        $drug_data['CLIC Visible on website'] = $drug->clic_visible_on_website;
        $drug_data['CLIC Rationale Code'] = $drug->clic_rationale_code;
        $drug_data['GWL (Y/N)'] = ($drug->gwl) ? 'Y' : 'N';
        $drug_data['GWL Tier'] = $drug->gwl_tier;
        $drug_data['GWL Action'] = $drug->gwl_action;
        $drug_data['GWL GF Period Code'] = $drug->gwl_gf_period_code;
        $drug_data['GWL ScreenCode'] = $drug->gwl_screencode;
        $drug_data['GWL Visible on website'] = $drug->gwl_visible_on_website;
        $drug_data['GWL Rationale Code'] = $drug->gwl_rationale_code;
        $drug_data['GWL (CADA) (Y/N)'] = ($drug->gwl_cada) ? 'Y' : 'N';
        $drug_data['GWL (CADA) Tier'] = $drug->gwl_cada_tier;
        $drug_data['GWL (CADA) Action'] = $drug->gwl_cada_action;
        $drug_data['GWL (CADA) ScreenCode'] = $drug->gwl_cada_screencode;
        $drug_data['GWL (CADA) Visible on website'] = $drug->gwl_cada_visible_on_website;
        $drug_data['GWL (CADA) Rationale Code'] = $drug->gwl_cada_rationale_code;
        $drug_data['CS Y/N'] = ($drug->cs) ? 'Y' : 'N';
        $drug_data['CS Tier'] = $drug->cs_tier;
        $drug_data['CS Action'] = $drug->cs_action;
        $drug_data['CS GF Period'] = $drug->cs_gf_period;
        $drug_data['CS GF Period Code'] = $drug->cs_gf_period_code;
        $drug_data['CS ScreenCode'] = $drug->cs_screencode;
        $drug_data['CS Visible on website'] = $drug->cs_visible_on_website;
        $drug_data['CS Rationale Code'] = $drug->cs_rationale_code;
        $drug_data['CS Notes'] = $drug->cs_notes;
        $drug_data['CS (IW) Y/N'] = ($drug->cs_iw) ? 'Y' : 'N';
        $drug_data['CS (IW) Tier'] = $drug->cs_iw_tier;
        $drug_data['CS (IW) Action'] = $drug->cs_iw_action;
        $drug_data['CS (IW) GF Period'] = $drug->cs_iw_gf_period;
        $drug_data['CS (IW) GF Period Code'] = $drug->cs_iw_gf_period_code;
        $drug_data['CS (IW) ScreenCode'] = $drug->cs_iw_screencode;
        $drug_data['CS (IW) Visible on website'] = $drug->cs_iw_visible_on_website;
        $drug_data['CS (IW) Rationale Code'] = $drug->cs_iw_rationale_code;
        $drug_data['JG (Y/N)'] = ($drug->jg) ? 'Y' : 'N';
        $drug_data['JG Tier'] = $drug->jg_tier;
        $drug_data['JG SA'] = $drug->jg_sa;
        $drug_data['JG Notes'] = $drug->jg_notes;
        $drug_data['JG Action'] = $drug->jg_actions;
        $drug_data['JG Explanation'] = $drug->jg_explanation;
        $drug_data['JG GF Period'] = $drug->jg_gf_period;
        $drug_data['JG QL'] = $drug->jg_ql;
        $drug_data['JG GF Period Code'] = $drug->jg_gf_period_code;
        $drug_data['JG ScreenCode'] = $drug->jg_screencode;
        $drug_data['JG Visible on website'] = $drug->jg_visible_on_website;
        $drug_data['JG Rationale Code'] = $drug->jg_rationale_code;
        $drug_data['Generic Name'] = $drug->generic_name;
        $drug_data['Generic Version of'] = $drug->generic_version_of;
        $drug_data['Screen Code'] = $drug->screen_code;
        $drug_data['NewScreenCode 1'] = $drug->newscreencode_1;
        $drug_data['Visible on website'] = $drug->visible_on_website;
        $drug_data['Blue Box'] = $drug->blue_box;
        $drug_data['Alternative DINs'] = $drug->alternative_dins;
        $drug_data['Alternative DINs Non-prescribed'] = $drug->alternative_dins_non_prescribed;
        $drug_data['Strength'] = $drug->strength;
        $drug_data['Form'] = $drug->form;
        $drug_data['Route of Administration'] = $drug->route_of_administration;
        $drug_data['Drug Sub-Type'] = $drug->drug_sub_type;
        $drug_data['Manufacturer'] = $drug->manufacturer;
        $drug_data['Discontinued date'] = $drug->discontinued_date;
        $drug_data['Notes'] = $drug->notes;
        $drug_data['RAMQ (Y/N/SA)'] = $drug->ramq;
        $drug_data['AHFS'] = $drug->ahfs;
        $drug_data['DC'] = $drug->dc;
        $drug_data['Schedule'] = $drug->schedule;
        $drug_data['A/M'] = $drug->a_m;
        $drug_data['TC'] = $drug->tc;
        $drug_data['Life Sustaining OTC (Y/N)'] = $drug->life_sustaining_otc;
        $drug_data['Life Style Drug (Y/N)'] = $drug->life_style_drug;
        $drug_data['Nom du médicament ou du produit'] = $drug->drug_or_product_name_french;
        $drug_data['Position sur Reformulary'] = $drug->reformulary_position_french;
        $drug_data['Affection médicale'] = ($disorder) ? $this->t($disorder->category) : '';
        $drug_data['Sous-affection médicale'] = ($disorder) ? $this->t($disorder->name) : '';
        $drug_data['Classe de médicaments'] = $drug->drug_class_french;
        $drug_data['Ingrédient actif'] = $drug->active_ingredient_french;
        $drug_data['Produit générique'] = $drug->generic_name_french;
        $drug_data['Produit innovateur de référence'] = $drug->generic_version_of_french;
        $drug_data['Boîte bleue'] = $drug->blue_box_french;
        $drug_data['Alternative DINs Non-prescribed (Fre)'] = $drug->alternative_dins_non_prescribed_french;
        $drug_data['Teneur'] = $drug->strength_french;
        $drug_data['Forme pharmaceutique'] = $drug->form_french;
        $drug_data['Test Strip'] = $drug->test_strip;
        $drug_data['PIN SLF'] = $drug->pin_slf;
        $drug_data['PIN CLIC'] = $drug->pin_clic;
        $drug_data['Gx  available on the market'] = $drug->gx_available_on_the_market;
        $drug_data['Notes on RG Select'] = $drug->notes_on_rg_select;
        $drug_data['Vaccines (used to protect against)'] = $drug->vaccines_used_to_protect_against;
        $drug_data['Vaccines (used to protect against) (Fre)'] = $drug->vaccines_used_to_protect_against_french;
        $drug_data['Rationale Code'] = $drug->rationale_code;
        $drug_data['Rationale (Show me "WHY" button) (Eng)'] = $drug->rationale;
        $drug_data['Rationale (Show me "WHY" button) (Fre)'] = $drug->rationale_french;
        $drug_data['Quantity Limits days'] = $drug->quantity_limits_days;
        $drug_data['Used For Code'] = $drug->used_for_code;
        $drug_data['Specialty drug'] = $drug->specialty_drug;
        $drug_data['SLFU'] = $drug->sluf;
        $drug_data['PREXDU'] = $drug->prexdu;
        $drug_data['Patient Support Program'] = $drug->patient_support_program;
        $drug_data['Special Distribution Program'] = $drug->special_distribution_program;

        return $drug_data;
    }
}
