<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NOCMain extends Model
{
    use HasFactory;
    use BaseModel;

    protected $table = 'noc_mains';
    protected $fillable = ['file_date', 'noc_number', 'noc_dp_din_product_id', 'noc_dp_din', 'noc_br_brandname', 'noc_crp_product_name', 'noc_pi_medic_ingr_eng_name', 'noc_pi_strength', 'noc_pf_form_eng_name', 'noc_pr_route_eng_desc', 'noc_eng_product_type', 'noc_manufacturer_name', 'noc_eng_reason_supplement', 'noc_date', 'noc_status_with_conditions', 'noc_eng_submission_type', 'noc_eng_therapeutic_class', 'noc_eng_submission_class'];

    public static function getValidationRules(array $values, self $model = null)
    {
        return [
        ];
    }
}
