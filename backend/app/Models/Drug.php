<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use OwenIt\Auditing\Contracts\Auditable;

class Drug extends Model implements Auditable
{
    use HasFactory;
    use BaseModel;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'ref_id',
        'modified',
        'alert_notifications',
        'din',
        'pin',
        'din_pin',
        'drug_or_product_name',
        'din_duplicate',
        'health_canada_drug_name',
        'reformulary',
        'rg_tier',
        'conditions',
        'drug_product_type',
        'medical_condition',
        'sub_medical_condition',
        'drug_class',
        'active_ingredient',
        'gf_period_code',
        'gf_period',
        'step_therapy',
        'quantity_limits',
        'action',
        'explanation',
        'slf',
        'slf_tier',
        'slf_action',
        'slf_external_notes',
        'slf_gf_period',
        'slf_ql',
        'slf_gf_period_code',
        'slf_screencode',
        'slf_visible_on_website',
        'slf_rationale_code',
        'clic',
        'clic_tier',
        'clic_action',
        'clic_external_notes',
        'clic_gf_period',
        'clic_targetted_letter',
        'clic_ql',
        'include_exclude',
        'sa',
        'clic_gf_period_code',
        'clic_screencode',
        'clic_visible_on_website',
        'clic_rationale_code',
        'gwl',
        'gwl_tier',
        'gwl_action',
        'gwl_gf_period_code',
        'gwl_screencode',
        'gwl_visible_on_website',
        'gwl_rationale_code',
        'gwl_cada',
        'gwl_cada_tier',
        'gwl_cada_action',
        'gwl_cada_screencode',
        'gwl_cada_visible_on_website',
        'gwl_cada_rationale_code',
        'cs',
        'cs_tier',
        'cs_action',
        'cs_external_notes',
        'cs_gf_period',
        'cs_gf_period_code',
        'cs_screencode',
        'cs_visible_on_website',
        'cs_rationale_code',
        'cs_notes',
        'cs_iw',
        'cs_iw_tier',
        'cs_iw_action',
        'cs_iw_gf_period',
        'cs_iw_gf_period_code',
        'cs_iw_screencode',
        'cs_iw_visible_on_website',
        'cs_iw_rationale_code',
        'jg',
        'jg_tier',
        'jg_sa',
        'jg_notes',
        'jg_action',
        'jg_explanation',
        'jg_gf_period',
        'jg_ql',
        'jg_gf_period_code',
        'jg_screencode',
        'jg_visible_on_website',
        'jg_rationale_code',
        'generic_name',
        'generic_version_of',
        'screen_code',
        'newscreencode_1',
        'visible_on_website',
        'blue_box',
        'alternative_dins',
        'alternative_dins_non_prescribed',
        'strength',
        'form',
        'route_of_administration',
        'drug_sub_type',
        'manufacturer',
        'discontinued_date',
        'notes',
        'ramq',
        'ahfs',
        'dc',
        'schedule',
        'a_m',
        'tc',
        'life_sustaining_otc',
        'life_style_drug',
        'drug_or_product_name_french',
        'reformulary_position_french',
        'medical_condition_french',
        'sub_medical_condition',
        'drug_class_french',
        'active_ingredient_french',
        'generic_name_french',
        'generic_version_of_french',
        'blue_box_french',
        'alternative_dins_non_prescribed_french',
        'strength_french',
        'form_french',
        'test_strip',
        'pin_slf',
        'pin_clic',
        'gx_available_on_the_market',
        'notes_on_rg_select',
        'vaccines_used_to_protect_against',
        'vaccines_used_to_protect_against_french',
        'rationale_code',
        'rationale',
        'rationale_french',
        'quantity_limits_days',
        'used_for_code',
        'specialty_drug',
        'slfu',
        'prexdu',
        'patient_support_program',
        'special_distribution_program',
    ];

    public static function getValidationRules(array $values, self $model = null)
    {
        $rc = [
            'ref_id' => ['nullable', 'string'],
            'modified' => ['nullable', 'string'],
            'alert_notifications' => ['nullable'],
            'din' => ['nullable', 'same:din_pin', 'prohibits:pin', 'digits:8'],
            'pin' => ['nullable', 'same:din_pin', 'prohibits:din'],
            'din_pin' => ['required', 'numeric'],
            'drug_or_product_name' => ['nullable'],
            'din_duplicate' => ['nullable'],
            'health_canada_drug_name' => ['nullable'],
            'reformulary' => ['nullable'],
            'rg_tier' => ['nullable'],
            'conditions' => ['nullable'],
            'drug_product_type' => ['nullable'],
            'medical_condition' => ['nullable'],
            'sub_medical_condition' => ['nullable'],
            'drug_class' => ['nullable'],
            'active_ingredient' => ['nullable'],
            'gf_period_code' => ['nullable'],
            'gf_period' => ['nullable'],
            'step_therapy' => ['nullable'],
            'quantity_limits' => ['nullable'],
            'action' => ['nullable'],
            'explanation' => ['nullable'],
            'slf' => ['nullable', 'boolean'],
            'slf_tier' => ['nullable'],
            'slf_action' => ['nullable'],
            'slf_external_notes' => ['nullable'],
            'slf_gf_period' => ['nullable'],
            'slf_ql' => ['nullable'],
            'slf_gf_period_code' => ['nullable'],
            'slf_screencode' => ['nullable'],
            'slf_visible_on_website' => ['nullable'],
            'slf_rationale_code' => ['nullable'],
            'clic' => ['nullable'],
            'clic_tier' => ['nullable'],
            'clic_action' => ['nullable'],
            'clic_external_notes' => ['nullable'],
            'clic_gf_period' => ['nullable'],
            'clic_targetted_letter' => ['nullable'],
            'clic_ql' => ['nullable'],
            'include_exclude' => ['nullable'],
            'sa' => ['nullable'],
            'clic_gf_period_code' => ['nullable'],
            'clic_screencode' => ['nullable'],
            'clic_visible_on_website' => ['nullable'],
            'clic_rationale_code' => ['nullable'],
            'gwl' => ['nullable'],
            'gwl_tier' => ['nullable'],
            'gwl_action' => ['nullable'],
            'gwl_gf_period_code' => ['nullable'],
            'gwl_screencode' => ['nullable'],
            'gwl_visible_on_website' => ['nullable'],
            'gwl_rationale_code' => ['nullable'],
            'gwl_cada' => ['nullable'],
            'gwl_cada_tier' => ['nullable'],
            'gwl_cada_action' => ['nullable'],
            'gwl_cada_screencode' => ['nullable'],
            'gwl_cada_visible_on_website' => ['nullable'],
            'gwl_cada_rationale_code' => ['nullable'],
            'cs' => ['nullable'],
            'cs_tier' => ['nullable'],
            'cs_action' => ['nullable'],
            'cs_external_notes' => ['nullable'],
            'cs_gf_period' => ['nullable'],
            'cs_gf_period_code' => ['nullable'],
            'cs_screencode' => ['nullable'],
            'cs_visible_on_website' => ['nullable'],
            'cs_rationale_code' => ['nullable'],
            'cs_notes' => ['nullable'],
            'cs_iw' => ['nullable'],
            'cs_iw_tier' => ['nullable'],
            'cs_iw_action' => ['nullable'],
            'cs_iw_gf_period' => ['nullable'],
            'cs_iw_gf_period_code' => ['nullable'],
            'cs_iw_screencode' => ['nullable'],
            'cs_iw_visible_on_website' => ['nullable'],
            'cs_iw_rationale_code' => ['nullable'],
            'jg' => ['nullable'],
            'jg_tier' => ['nullable'],
            'jg_sa' => ['nullable'],
            'jg_notes' => ['nullable'],
            'jg_action' => ['nullable'],
            'jg_explanation' => ['nullable'],
            'jg_gf_period' => ['nullable'],
            'jg_ql' => ['nullable'],
            'jg_gf_period_code' => ['nullable'],
            'jg_screencode' => ['nullable'],
            'jg_visible_on_website' => ['nullable'],
            'jg_rationale_code' => ['nullable'],
            'generic_name' => ['nullable'],
            'generic_version_of' => ['nullable', 'exists:drugs,drug_or_product_name'],
            'screen_code' => ['nullable'],
            'newscreencode_1' => ['nullable'],
            'visible_on_website' => ['nullable'],
            'blue_box' => ['nullable'],
            'alternative_dins' => ['nullable'],
            'alternative_dins_non_prescribed' => ['nullable'],
            'strength' => ['nullable'],
            'form' => ['nullable'],
            'route_of_administration' => ['nullable'],
            'drug_sub_type' => ['nullable'],
            'manufacturer' => ['nullable'],
            'discontinued_date' => ['nullable'],
            'notes' => ['nullable'],
            'ramq' => ['nullable'],
            'ahfs' => ['nullable'],
            'dc' => ['nullable'],
            'schedule' => ['nullable'],
            'a_m' => ['nullable'],
            'tc' => ['nullable'],
            'life_sustaining_otc' => ['nullable'],
            'life_style_drug' => ['nullable'],
            'drug_or_product_name_french' => ['nullable'],
            'reformulary_position_french' => ['nullable'],
            'medical_condition_french' => ['nullable'],
            'subm_medical_condition_french' => ['nullable'],
            'drug_class_french' => ['nullable'],
            'active_ingredient_french' => ['nullable'],
            'generic_name_french' => ['nullable'],
            'generic_version_of_french' => ['nullable'],
            'blue_box_french' => ['nullable'],
            'alternative_dins_non_prescribed_french' => ['nullable'],
            'strength_french' => ['nullable'],
            'form_french' => ['nullable'],
            'test_strip' => ['nullable'],
            'pin_slf' => ['nullable'],
            'pin_clic' => ['nullable'],
            'gx_available_on_the_market' => ['nullable'],
            'notes_on_rg_select' => ['nullable'],
            'vaccines_used_to_protect_against' => ['nullable'],
            'vaccines_used_to_protect_against_french' => ['nullable'],
            'rationale_code' => ['nullable'],
            'rationale' => ['nullable'],
            'rationale_french' => ['nullable'],
            'quantity_limits_days' => ['nullable'],
            'used_for_code' => ['nullable'],
            'specialty_drug' => ['nullable'],
            'slfu' => ['nullable'],
            'prexdu' => ['nullable'],
            'patient_support_program' => ['nullable'],
            'special_distribution_program' => ['nullable'],
        ];

        $rules['din']['unique'] = Rule::unique('drugs', 'din');
        if (!empty($model) && $id = (int) $model->id) {
            $rules['din']['unique'] = $rules['din']['unique']->ignore($id);
        }

        return $rc;
    }

    public function disorders()
    {
        return $this->belongsToMany(Disorder::class, 'disorder_drug', 'drug_id', 'disorder_id')->withTimestamps();
    }

    /**
     * @deprecated use getFieldsData() instead
     */
    public static function getFrenchFields()
    {
        $rc = [];
        $rc[] = ['excel_column_name' => 'Nom du médicament ou du produit', 'db_col_name' => 'drug_or_product_name', 'db_col_name_french' => 'drug_or_product_name_french'];
        $rc[] = ['excel_column_name' => 'Position sur Reformulary', 'db_col_name' => 'reformulary_position', 'db_col_name_french' => 'reformulary_position_french'];
        $rc[] = ['excel_column_name' => 'Affection médicale', 'db_col_name' => 'medical_condition', 'db_col_name_french' => 'medical_condition_french'];
        $rc[] = ['excel_column_name' => 'Sous-affection médicale', 'db_col_name' => 'sub_medical_condition', 'db_col_name_french' => 'sub_medical_condition_french'];
        $rc[] = ['excel_column_name' => 'Classe de médicaments', 'db_col_name' => 'drug_class', 'db_col_name_french' => 'drug_class_french'];
        $rc[] = ['excel_column_name' => 'Ingrédient actif', 'db_col_name' => 'active_ingredient', 'db_col_name_french' => 'active_ingredient_french'];
        $rc[] = ['excel_column_name' => 'Produit générique', 'db_col_name' => 'generic_name', 'db_col_name_french' => 'generic_name_french'];
        $rc[] = ['excel_column_name' => 'Produit innovateur de référence', 'db_col_name' => 'generic_version_of', 'db_col_name_french' => 'generic_version_of_french'];
        $rc[] = ['excel_column_name' => 'Boîte bleue', 'db_col_name' => 'blue_box', 'db_col_name_french' => 'blue_box_french'];
        $rc[] = ['excel_column_name' => 'Alternative DINs Non-prescribed (Fre)', 'db_col_name' => 'alternative_dins_non_prescribed', 'db_col_name_french' => 'alternative_dins_non_prescribed_french'];
        $rc[] = ['excel_column_name' => 'Teneur', 'db_col_name' => 'strength', 'db_col_name_french' => 'strength_french'];
        $rc[] = ['excel_column_name' => 'Forme pharmaceutique', 'db_col_name' => 'form', 'db_col_name_french' => 'form_french'];
        $rc[] = ['excel_column_name' => 'Vaccines (used to protect against) (Fre)', 'db_col_name' => 'vaccines_used_to_protect_against', 'db_col_name_french' => 'vaccines_used_to_protect_against_french'];

        return $rc;
    }

    public static function getFieldsData()
    {
        $rc = [];

        $rc[] = ['excel_col_name' => 'RefID', 'db_col_name' => 'ref_id'];
        $rc[] = ['excel_col_name' => 'Modified y/N', 'db_col_name' => 'modified'];
        $rc[] = ['excel_col_name' => 'Alert Notifications', 'db_col_name' => 'alert_notifications'];
        $rc[] = ['excel_col_name' => 'DIN', 'db_col_name' => 'din'];
        $rc[] = ['excel_col_name' => 'PIN', 'db_col_name' => 'pin'];
        $rc[] = ['excel_col_name' => 'DIN/PIN', 'db_col_name' => 'din_pin'];
        $rc[] = ['excel_col_name' => 'Drug or Product Name', 'db_col_name' => 'drug_or_product_name'];
        $rc[] = ['excel_col_name' => 'DIN Duplicate (D)', 'db_col_name' => 'din_duplicate'];
        $rc[] = ['excel_col_name' => 'Health Canada Drug Name', 'db_col_name' => 'health_canada_drug_name'];
        $rc[] = ['excel_col_name' => 'Reformulary (Y/N)', 'db_col_name' => 'reformulary'];
        $rc[] = ['excel_col_name' => 'RG Tier', 'db_col_name' => 'rg_tier'];
        $rc[] = ['excel_col_name' => 'Conditions (Y/N)', 'db_col_name' => 'conditions'];
        $rc[] = ['excel_col_name' => 'Medical Condition', 'db_col_name' => 'medical_condition'];
        $rc[] = ['excel_col_name' => 'Sub-medical Condition', 'db_col_name' => 'sub_medical_condition'];
        $rc[] = ['excel_col_name' => 'Drug/Product Type', 'db_col_name' => 'drug_product_type'];
        $rc[] = ['excel_col_name' => 'Drug Class', 'db_col_name' => 'drug_class'];
        $rc[] = ['excel_col_name' => 'Active Ingredient', 'db_col_name' => 'active_ingredient'];
        $rc[] = ['excel_col_name' => 'GF Period Code', 'db_col_name' => 'gf_period_code'];
        $rc[] = ['excel_col_name' => 'GF Period', 'db_col_name' => 'gf_period'];
        $rc[] = ['excel_col_name' => 'Step Therapy (Y/N)', 'db_col_name' => 'step_therapy'];
        $rc[] = ['excel_col_name' => 'Quantity Limits', 'db_col_name' => 'quantity_limits'];
        $rc[] = ['excel_col_name' => 'Action', 'db_col_name' => 'action'];
        $rc[] = ['excel_col_name' => 'Explanation', 'db_col_name' => 'explanation'];
        $rc[] = ['excel_col_name' => 'SLF (Y/N)', 'db_col_name' => 'slf'];
        $rc[] = ['excel_col_name' => 'SLF Tier', 'db_col_name' => 'slf_tier'];
        $rc[] = ['excel_col_name' => 'SLF Action', 'db_col_name' => 'slf_action'];
        $rc[] = ['excel_col_name' => 'SLF GF Period', 'db_col_name' => 'slf_gf_period'];
        $rc[] = ['excel_col_name' => 'SLF QL', 'db_col_name' => 'slf_ql'];
        $rc[] = ['excel_col_name' => 'SLF GF Period Code', 'db_col_name' => 'slf_gf_period_code'];
        $rc[] = ['excel_col_name' => 'SLF ScreenCode', 'db_col_name' => 'slf_screencode'];
        $rc[] = ['excel_col_name' => 'SLF Visible on website', 'db_col_name' => 'slf_visible_on_website'];
        $rc[] = ['excel_col_name' => 'SLF Rationale Code', 'db_col_name' => 'slf_rationale_code'];
        $rc[] = ['excel_col_name' => 'CLIC (Y/N)', 'db_col_name' => 'clic'];
        $rc[] = ['excel_col_name' => 'CLIC Tier', 'db_col_name' => 'clic_tier'];
        $rc[] = ['excel_col_name' => 'CLIC Action', 'db_col_name' => 'clic_action'];
        $rc[] = ['excel_col_name' => 'CLIC GF Period', 'db_col_name' => 'clic_gf_period'];
        $rc[] = ['excel_col_name' => 'CLIC Targetted letter (Y/X)', 'db_col_name' => 'clic_targetted_letter'];
        $rc[] = ['excel_col_name' => 'CLIC QL', 'db_col_name' => 'clic_ql'];
        $rc[] = ['excel_col_name' => 'Include/Exclude', 'db_col_name' => 'include_exclude'];
        $rc[] = ['excel_col_name' => 'SA (SA or blank)', 'db_col_name' => 'sa'];
        $rc[] = ['excel_col_name' => 'CLIC GF Period Code', 'db_col_name' => 'clic_gf_period_code'];
        $rc[] = ['excel_col_name' => 'CLIC ScreenCode', 'db_col_name' => 'clic_screencode'];
        $rc[] = ['excel_col_name' => 'CLIC Visible on website', 'db_col_name' => 'clic_visible_on_website'];
        $rc[] = ['excel_col_name' => 'CLIC Rationale Code', 'db_col_name' => 'clic_rationale_code'];
        $rc[] = ['excel_col_name' => 'GWL (Y/N)', 'db_col_name' => 'gwl'];
        $rc[] = ['excel_col_name' => 'GWL Tier', 'db_col_name' => 'gwl_tier'];
        $rc[] = ['excel_col_name' => 'GWL Action', 'db_col_name' => 'gwl_action'];
        $rc[] = ['excel_col_name' => 'GWL GF Period Code', 'db_col_name' => 'gwl_gf_period_code'];
        $rc[] = ['excel_col_name' => 'GWL ScreenCode', 'db_col_name' => 'gwl_screencode'];
        $rc[] = ['excel_col_name' => 'GWL Visible on website', 'db_col_name' => 'gwl_visible_on_website'];
        $rc[] = ['excel_col_name' => 'GWL Rationale Code', 'db_col_name' => 'gwl_rationale_code'];
        $rc[] = ['excel_col_name' => 'GWL (CADA) (Y/N)', 'db_col_name' => 'gwl_cada'];
        $rc[] = ['excel_col_name' => 'GWL (CADA) Tier', 'db_col_name' => 'gwl_cada_tier'];
        $rc[] = ['excel_col_name' => 'GWL (CADA) Action', 'db_col_name' => 'gwl_cada_action'];
        $rc[] = ['excel_col_name' => 'GWL (CADA) ScreenCode', 'db_col_name' => 'gwl_cada_screencode'];
        $rc[] = ['excel_col_name' => 'GWL (CADA) Visible on website', 'db_col_name' => 'gwl_cada_visible_on_website'];
        $rc[] = ['excel_col_name' => 'GWL (CADA) Rationale Code', 'db_col_name' => 'gwl_cada_rationale_code'];
        $rc[] = ['excel_col_name' => 'CS Y/N', 'db_col_name' => 'cs'];
        $rc[] = ['excel_col_name' => 'CS Tier', 'db_col_name' => 'cs_tier'];
        $rc[] = ['excel_col_name' => 'CS Action', 'db_col_name' => 'cs_action'];
        $rc[] = ['excel_col_name' => 'CS GF Period', 'db_col_name' => 'cs_gf_period'];
        $rc[] = ['excel_col_name' => 'CS GF Period Code', 'db_col_name' => 'cs_gf_period_code'];
        $rc[] = ['excel_col_name' => 'CS ScreenCode', 'db_col_name' => 'cs_screencode'];
        $rc[] = ['excel_col_name' => 'CS Visible on website', 'db_col_name' => 'cs_visible_on_website'];
        $rc[] = ['excel_col_name' => 'CS Rationale Code', 'db_col_name' => 'cs_rationale_code'];
        $rc[] = ['excel_col_name' => 'CS Notes', 'db_col_name' => 'cs_notes'];
        $rc[] = ['excel_col_name' => 'CS (IW) Y/N', 'db_col_name' => 'cs_iw'];
        $rc[] = ['excel_col_name' => 'CS (IW) Tier', 'db_col_name' => 'cs_iw_tier'];
        $rc[] = ['excel_col_name' => 'CS (IW) Action', 'db_col_name' => 'cs_iw_action'];
        $rc[] = ['excel_col_name' => 'CS (IW) GF Period', 'db_col_name' => 'cs_iw_gf_period'];
        $rc[] = ['excel_col_name' => 'CS (IW) GF Period Code', 'db_col_name' => 'cs_iw_gf_period_code'];
        $rc[] = ['excel_col_name' => 'CS (IW) ScreenCode', 'db_col_name' => 'cs_iw_screencode'];
        $rc[] = ['excel_col_name' => 'CS (IW) Visible on website', 'db_col_name' => 'cs_iw_visible_on_website'];
        $rc[] = ['excel_col_name' => 'CS (IW) Rationale Code', 'db_col_name' => 'cs_iw_rationale_code'];
        $rc[] = ['excel_col_name' => 'JG (Y/N)', 'db_col_name' => 'jg'];
        $rc[] = ['excel_col_name' => 'JG Tier', 'db_col_name' => 'jg_tier'];
        $rc[] = ['excel_col_name' => 'JG SA', 'db_col_name' => 'jg_sa'];
        $rc[] = ['excel_col_name' => 'JG Notes', 'db_col_name' => 'jg_notes'];
        $rc[] = ['excel_col_name' => 'JG Action', 'db_col_name' => 'jg_action'];
        $rc[] = ['excel_col_name' => 'JG Explanation', 'db_col_name' => 'jg_explanation'];
        $rc[] = ['excel_col_name' => 'JG GF Period', 'db_col_name' => 'jg_gf_period'];
        $rc[] = ['excel_col_name' => 'JG QL', 'db_col_name' => 'jg_ql'];
        $rc[] = ['excel_col_name' => 'JG GF Period Code', 'db_col_name' => 'jg_gf_period_code'];
        $rc[] = ['excel_col_name' => 'JG ScreenCode', 'db_col_name' => 'jg_screencode'];
        $rc[] = ['excel_col_name' => 'JG Visible on website', 'db_col_name' => 'jg_visible_on_website'];
        $rc[] = ['excel_col_name' => 'JG Rationale Code', 'db_col_name' => 'jg_rationale_code'];
        $rc[] = ['excel_col_name' => 'Generic Name', 'db_col_name' => 'generic_name'];
        $rc[] = ['excel_col_name' => 'Generic Version of', 'db_col_name' => 'generic_version_of'];
        $rc[] = ['excel_col_name' => 'Screen Code', 'db_col_name' => 'screen_code'];
        $rc[] = ['excel_col_name' => 'NewScreenCode 1', 'db_col_name' => 'newscreencode_1'];
        $rc[] = ['excel_col_name' => 'Visible on website', 'db_col_name' => 'visible_on_website'];
        $rc[] = ['excel_col_name' => 'Blue Box', 'db_col_name' => 'blue_box'];
        $rc[] = ['excel_col_name' => 'Alternative DINs', 'db_col_name' => 'alternative_dins'];
        $rc[] = ['excel_col_name' => 'Alternative DINs Non-prescribed', 'db_col_name' => 'alternative_dins_non_prescribed'];
        $rc[] = ['excel_col_name' => 'Strength', 'db_col_name' => 'strength'];
        $rc[] = ['excel_col_name' => 'Form', 'db_col_name' => 'form'];
        $rc[] = ['excel_col_name' => 'Route of Administration', 'db_col_name' => 'route_of_administration'];
        $rc[] = ['excel_col_name' => 'Drug Sub-Type', 'db_col_name' => 'drug_sub_type'];
        $rc[] = ['excel_col_name' => 'Manufacturer', 'db_col_name' => 'manufacturer'];
        $rc[] = ['excel_col_name' => 'Discontinued date', 'db_col_name' => 'discontinued_date'];
        $rc[] = ['excel_col_name' => 'Notes', 'db_col_name' => 'notes'];
        $rc[] = ['excel_col_name' => 'RAMQ (Y/N/SA)', 'db_col_name' => 'ramq'];
        $rc[] = ['excel_col_name' => 'AHFS', 'db_col_name' => 'ahfs'];
        $rc[] = ['excel_col_name' => 'DC', 'db_col_name' => 'dc'];
        $rc[] = ['excel_col_name' => 'Schedule', 'db_col_name' => 'schedule'];
        $rc[] = ['excel_col_name' => 'A/M', 'db_col_name' => 'a_m'];
        $rc[] = ['excel_col_name' => 'TC', 'db_col_name' => 'tc'];
        $rc[] = ['excel_col_name' => 'Life Sustaining OTC (Y/N)', 'db_col_name' => 'life_sustaining_otc'];
        $rc[] = ['excel_col_name' => 'Life Style Drug (Y/N)', 'db_col_name' => 'life_style_drug'];
        $rc[] = ['excel_col_name' => 'Nom du médicament ou du produit', 'db_col_name' => 'drug_or_product_name_french', 'db_col_translation_of' => 'drug_or_product_name'];
        $rc[] = ['excel_col_name' => 'Position sur Reformulary', 'db_col_name' => 'reformulary_position_french', 'db_col_translation_of' => 'reformulary_position'];
        $rc[] = ['excel_col_name' => 'Affection médicale', 'db_col_name' => 'medical_condition_french', 'db_col_translation_of' => 'medical_condition'];
        $rc[] = ['excel_col_name' => 'Sous-affection médicale', 'db_col_name' => 'sub_medical_condition_french', 'db_col_translation_of' => 'sub_medical_condition'];
        $rc[] = ['excel_col_name' => 'Classe de médicaments', 'db_col_name' => 'drug_class_french', 'db_col_translation_of' => 'drug_class'];
        $rc[] = ['excel_col_name' => 'Ingrédient actif', 'db_col_name' => 'active_ingredient_french', 'db_col_translation_of' => 'active_ingredient'];
        $rc[] = ['excel_col_name' => 'Produit générique', 'db_col_name' => 'generic_name_french', 'db_col_translation_of' => 'generic_name'];
        $rc[] = ['excel_col_name' => 'Produit innovateur de référence', 'db_col_name' => 'generic_version_of_french', 'db_col_translation_of' => 'generic_version_of'];
        $rc[] = ['excel_col_name' => 'Boîte bleue', 'db_col_name' => 'blue_box_french', 'db_col_translation_of' => 'blue_box'];
        $rc[] = ['excel_col_name' => 'Alternative DINs Non-prescribed (Fre)', 'db_col_name' => 'alternative_dins_non_prescribed_french', 'db_col_translation_of' => 'alternative_dins_non_prescribed'];
        $rc[] = ['excel_col_name' => 'Teneur', 'db_col_name' => 'strength_french', 'db_col_translation_of' => 'strength'];
        $rc[] = ['excel_col_name' => 'Forme pharmaceutique', 'db_col_name' => 'form_french', 'db_col_translation_of' => 'form'];
        $rc[] = ['excel_col_name' => 'Test Strip', 'db_col_name' => 'test_strip'];
        $rc[] = ['excel_col_name' => 'PIN SLF', 'db_col_name' => 'pin_slf'];
        $rc[] = ['excel_col_name' => 'PIN CLIC', 'db_col_name' => 'pin_clic'];
        $rc[] = ['excel_col_name' => 'Gx  available on the market', 'db_col_name' => 'gx_available_on_the_market'];
        $rc[] = ['excel_col_name' => 'Notes on RG Select', 'db_col_name' => 'notes_on_rg_select'];
        $rc[] = ['excel_col_name' => 'Vaccines (used to protect against)', 'db_col_name' => 'vaccines_used_to_protect_against'];
        $rc[] = ['excel_col_name' => 'Vaccines (used to protect against) (Fre)', 'db_col_name' => 'vaccines_used_to_protect_against_french', 'db_col_translation_of' => 'vaccines_used_to_protect_against'];
        $rc[] = ['excel_col_name' => 'Rationale Code', 'db_col_name' => 'rationale_code'];
        $rc[] = ['excel_col_name' => 'Rationale (Show me "WHY" button) (Eng)', 'db_col_name' => 'rationale'];
        $rc[] = ['excel_col_name' => 'Rationale (Show me "WHY" button) (Fre)', 'db_col_name' => 'rationale_french', 'db_col_translation_of' => 'rationale'];
        $rc[] = ['excel_col_name' => 'Quantity Limits days', 'db_col_name' => 'quantity_limits_days'];
        $rc[] = ['excel_col_name' => 'Used For Code', 'db_col_name' => 'used_for_code'];
        $rc[] = ['excel_col_name' => 'Specialty drug', 'db_col_name' => 'specialty_drug'];
        $rc[] = ['excel_col_name' => 'SLFU', 'db_col_name' => 'sluf'];
        $rc[] = ['excel_col_name' => 'PREXDU', 'db_col_name' => 'prexdu'];
        $rc[] = ['excel_col_name' => 'Patient Support Program', 'db_col_name' => 'patient_support_program'];
        $rc[] = ['excel_col_name' => 'Special Distribution Program', 'db_col_name' => 'special_distribution_program'];

        return $rc;
    }
}
