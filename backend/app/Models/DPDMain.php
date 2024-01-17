<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DPDMain extends Model
{
    use HasFactory;
    use BaseModel;

    protected $table = 'dpd_mains';

    protected $fillable = ['drug_code', 'din', 'brandname', 'aigroupno', 'numbersofais', 'last_update_date', 'filestatus', 'filedate', 'manufacturer', 'companycity', 'companystreet', 'companycountry', 'currentstatus', 'historydate', 'atcnumber', 'atc', 'ingredient', 'biosimilarbiologicdrug', 'strength', 'form', 'routeofadmin', 'schedule', 'aistrengthform', 'aistrengthform_cnt', 'drugsource', 'ramq_flag', 'bc', 'ab', 'sk', 'mb', 'on', 'qc', 'ns', 'nb', 'pei', 'nl', 'ahfs'];

    public static function getValidationRules(array $values, self $model = null)
    {
        return [
        ];
    }
}
